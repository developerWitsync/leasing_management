<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 21/2/19
 * Time: 11:51 AM
 */

namespace App\Http\Controllers;
use App\Lease;
use App\Mail\SubscriptionInvoice;
use App\SubscriptionPlans;
use App\UserSubscription;
use Carbon\Carbon;
use Validator;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;
use Mail;

class UpgradeController extends Controller
{
    public $breadcrumbs;
    public $provider;
    public function __construct()
    {
        $this->provider = new ExpressCheckout();
        $this->breadcrumbs = [
            [
                'link' => route('home'),
                'title' => 'Dashboard'
            ],
            [
                'link' => route('plan.index'),
                'title' => 'Upgrade Subscription Plan'
            ]
        ];
    }

    /**
     * renders the upgrade plan page for the user...
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        try{
            $breadcrumbs= $this->breadcrumbs;
            //check if the logged in user have purchased a plan or not
            $subscription = UserSubscription::query()->whereIn('user_id', getDependentUserIds())
                ->where('payment_status', '<>', 'pending')
                ->where('payment_status', '<>', 'Cancelled')
                ->orderBy('created_at', 'desc')
                ->first();

            $already_created_leases = Lease::query()->whereIn('business_account_id', getDependentUserIds())
                //->where('status', '=', '1')
                ->count();

            $plans = SubscriptionPlans::query()->where('is_custom', '=', '0')->get();

            $custom_plan = SubscriptionPlans::query()
                ->where('is_custom', '=', '1')
                ->where('email', '=', getParentDetails()->email)
                ->first();

            return view('plan.index', compact(
                'breadcrumbs',
                'subscription',
                'already_created_leases',
                'plans',
                'custom_plan'
            ));
        } catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * user will land to this function in case the user cancels the purchase on paypal...
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(){
        return redirect(route('plan.index'))->with('error', 'Your transaction has been cancelled.');
    }

    /**
     * renders the pop up to show the upgrade downgrade subscription pop up once the user is logged in....
     * @param $plan
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function subscriptionSelection($plan, Request $request){
        try{
            if($request->ajax()){

                if($plan){

                    $selected_package = SubscriptionPlans::query()
                        ->where('slug', '=', $plan)
                        ->firstOrFail();

                } else {
                    return response()->json(['status' => false, 'message' => 'Invalid Request.', 'errorCode' => 'package_error'], 200);
                }

                $action = null;
                if($request->has('action')){
                    $action = $request->action;
                }

                if($action == 'downgrade') {
                    //fetch the already created lease assets
                    $already_created_leases = Lease::query()->whereIn('business_account_id', getDependentUserIds())->count();
                    if($selected_package->available_leases <= $already_created_leases) {
                        return response()->json(['status'=> false, 'message' => 'You cannot downgrade to this plan as you have already created more than '. $selected_package->available_leases.' leases.']);
                    }
                }

                $view = view('plan._subscription_selection', compact(
                    'selected_package',
                    'action'
                ))->render();

                return response()->json([
                    'status' => true,
                    'view' => $view
                ], 200);

            } else {
                abort(404);
            }

        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * calculate the adjustments for the upgrades/down-grade for the user...
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function showadjustments(Request $request){
        try{
            if($request->ajax()){
                $plan = $request->plan;
                if($plan){

                    $months = $request->months;

                    $selected_package = SubscriptionPlans::query()->findOrFail($plan);

                    $coupon_code = null;
                    if($request->has('coupon_code') && trim($request->coupon_code)!=""){
                        $coupon_code = strtoupper(trim($request->coupon_code));
                    }

                    $action = null;
                    if($request->has('action')){
                        $action = $request->action;
                    }
                    $credit_or_balance = calculateAdjustedAmountForUpgradeDowngrade($selected_package, $months, $coupon_code, $action);

                    return response()->json($credit_or_balance, 200);

                } else {
                    return response()->json(['status' => false, 'message' => 'Invalid Request.', 'errorCode' => 'package_error'], 200);
                }
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * returns the paypal redirect link in case the user needs to pay...
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchase(Request $request){
        try{
            if($request->ajax()){

                $validator = Validator::make($request->all(), [
                    'months' => 'required|numeric',
                    'plan' => 'required|exists:subscription_plans,id'
                ]);

                if($validator->fails()){
                    return response()->json(['status' => false,'errors' => $validator->errors()], 200);
                }

                $package = SubscriptionPlans::query()->findOrFail($request->plan);

                $coupon_code = null;
                if($request->has('coupon_code') && trim($request->coupon_code)!=""){
                    $coupon_code = strtoupper(trim($request->coupon_code));
                }
                $action = null;
                if($request->has('action')){
                    $action = $request->action;
                }
                //get the adjusted amount if applicable it can be -ve, +ve or 0
                $adjusted_amount  = calculateAdjustedAmountForUpgradeDowngrade($package, $request->months, $coupon_code, $action);
                $credits = getParentDetails()->credit_balance;
                $send_to_paypal = false;
                if($adjusted_amount['status']){
                    if($adjusted_amount['balance'] > 0){
                        $send_to_paypal = false;
                    } elseif($adjusted_amount['balance'] < 0) {
                        if($credits >= (-1 * $adjusted_amount['balance'])) {
                            $send_to_paypal = false;
                        } else {
                            $send_to_paypal = true;
                        }

                    } else {
                        if($credits >= (-1 * $adjusted_amount['balance'])) {
                            $send_to_paypal = false;
                        } else {
                            $send_to_paypal = true;
                        }
                    }
                } else {
                    return response()->json(['status' => false, 'errorMessage' => $adjusted_amount['message']], 200);
                }

                //need to create an entry to the user_subscription table...
                if (!is_null($package->validity)) {
                    //this means that the plan is trial plan
                    $expiry_date = Carbon::today()->addDays($package->validity)->subDays(1)->format('Y-m-d');
                } else {
                    //will expires after 1 year
                    $expiry_date = Carbon::today()->addYear($request->months / 12)->subDays(1)->format('Y-m-d');
                }

                $renewal_date = Carbon::parse($expiry_date)->addDays(1)->format('Y-m-d');
                $user_subscription = UserSubscription::create([
                    'plan_id' => $package->id,
                    'user_id' => auth()->user()->id,
                    'paid_amount' => $package->price * $request->months,
                    'subscription_expire_at' => $expiry_date,
                    'subscription_renewal_at' => $renewal_date,
                    'payment_status' => 'pending',
                    'subscription_years' => ($request->months / 12),
                    'coupon_code'   => $coupon_code,
                ]);

                if ($package->price_plan_type == '1' && is_null($package->price)) {
                    $user_subscription->payment_status = "Completed";
                    $user_subscription->save();
                    session()->flash('status', 'Congratulations! Your plan has been activated.');
                    //need to send the invoice to the user for the purchase of the plan...
                    Mail::to($user_subscription->user)->queue(new SubscriptionInvoice($user_subscription));
                    return response()->json(['status' => true, 'redirect_link' => route('plan.index')], 200);
                } elseif ($package->price_plan_type == '1' && !is_null($package->price) || ($package->price_plan_type == '2' && $package->is_custom == '1')) {
                    if(!$send_to_paypal){
                        //need to send the invoice directly to the user
                        $user_subscription->payment_status      = "Completed";
                        $user_subscription->discounted_amount   = round(($package->annual_discount / 100) * ($package->price * 12), 2);
                        $user_subscription->credits_used        = $credits;
                        $user_subscription->adjusted_amount     = $adjusted_amount['adjusted_amount'];
                        $user_subscription->coupon_discount     = $adjusted_amount['coupon_discount'];
                        $user_subscription->save();

                        //update the credit_balance for the user
                        updateCreditBalanceForParent($adjusted_amount);
                        //need to send the invoice to the user for the purchase of the plan...
                        Mail::to($user_subscription->user)->queue(new SubscriptionInvoice($user_subscription));
                        session()->flash('status', 'Congratulations! Your plan has been activated. We have also sent an invoice to your registered email.');
                        return response()->json(['status' => true, 'redirect_link' => route('plan.index')], 200);
                    } else {
                        $link = generatePaypalExpressCheckoutLink($package, $user_subscription, route('plan.purchase.success'), route('plan.purchase.cancel'), null,$adjusted_amount, $request->months, $action);
                        if($link) {
                            return response()->json(['status' => true, 'redirect_link' => $link], 200);
                        } else {
                            return response()->json(['status' => false, 'errorMessage' => 'Something went wrong with paypal.Please try again.'], 200);
                        }
                    }
                } else {
                    // the selected plan is enterprise plan and the user will have to communicate with the admin,,,,

                }
            } else {
                abort(404);
            }
        } catch (\Exception $e){
            abort(404);
        }
    }

    /**
     * user will be returned to the below function when the payment on paypal has been done successfully...
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function success(Request $request){
        try{
            // check if payment is recurring
            $recurring = $request->input('recurring', false) ? true : false;
            $token = $request->get('token');
            $PayerID = $request->get('PayerID');
            // to get the payment details from paypal
            $response = $this->provider->getExpressCheckoutDetails($token);
            if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
                return redirect('/')->with('error','Error processing PayPal payment.');
            }
            // invoice id is stored in INVNUM which will be the id of the invoice
            $invoice_id = $response['INVNUM'];
            $subscription = UserSubscription::query()->find($invoice_id);
            $transaction_id =  "";
            // check if our payment is recurring
            if ($recurring === true) {
                // if recurring then we need to create the subscription
                // you can create monthly or yearly subscriptions
                //$response = $this->provider->createMonthlySubscription($response['TOKEN'], $response['AMT'], $cart['subscription_desc']);
                //$status = 'Invalid';
                // if after creating the subscription paypal responds with activeprofile or pendingprofile
                // we are good to go and we can set the status to Processed, else status stays Invalid
                //if (!empty($response['PROFILESTATUS']) && in_array($response['PROFILESTATUS'], ['ActiveProfile', 'PendingProfile'])) {
                   // $status = 'Processed';
                //}
            } else {
                // if payment is not recurring just perform transaction on PayPal
                // and get the payment status
                $payment_status = $this->provider->doExpressCheckoutPayment(json_decode($subscription->purchased_items, true), $token, $PayerID);
                $transaction_id = $payment_status['PAYMENTINFO_0_TRANSACTIONID'];
                $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];

            }
            // set invoice status
            $subscription->payment_status = $status;
            $subscription->geteway_transasction_id = $transaction_id;
            $subscription->save();
            if ($subscription->payment_status != "pending") {
                //update the used credits here
                //this code should be moved to the ipn
                $user = getParentDetails();
                $user->credit_balance = $user->credit_balance - $subscription->credits_used;
                $user->save();
                return redirect(route('plan.index'))->with('status', 'Congratulations! Your plan has been activated. We have also sent an invoice to your registered email.');
            }
            return redirect(route('plan.index'))->with('error', 'Error processing PayPal payment for Order ' . $subscription->id . '!');
        } catch (\Exception $e){
            abort(404);
        }
    }
}