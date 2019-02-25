<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 21/2/19
 * Time: 11:51 AM
 */

namespace App\Http\Controllers;
use App\Lease;
use App\SubscriptionPlans;
use App\User;
use App\UserSubscription;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Resource_;
use Srmklive\PayPal\Services\ExpressCheckout;

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
        $breadcrumbs= $this->breadcrumbs;
        //check if the logged in user have purchased a plan or not
        $subscription = UserSubscription::query()->whereIn('user_id', getDependentUserIds())
            ->where('payment_status', '<>', 'pending')
            ->where('payment_status', '<>', 'Cancelled')
            ->orderBy('created_at', 'desc')
            ->first();
        $already_created_leases = Lease::query()->whereIn('business_account_id', getDependentUserIds())
            ->where('status', '=', '1')
            ->count();
        $plans = SubscriptionPlans::query()->get();
        return view('plan.index', compact(
            'breadcrumbs',
            'subscription',
            'already_created_leases',
            'plans'
        ));
    }

    /**
     * upgrade downgrade info as per the selected plan by the user...
     * @param $plan
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function changePlanDetails($plan, Request $request){
        try{
            if($request->ajax()){
                $package = SubscriptionPlans::query()->where('slug', '=', $plan)->firstOrFail();
                //need to check if the user is upgrading or downgrading the plan and need to send the amount to the paypal as per the calculations...
                $credit_or_balance = calculateCreditBalanceForUpgradeDowngrade($package);
                if($credit_or_balance['status']){
                    $view = view('plan._upgrade_downgrade', compact(
                        'credit_or_balance',
                        'plan'
                    ))->render();
                    return response()->json([
                        'status' => true,
                        'view' => $view
                    ], 200);
                } else {
                    return response()->json($credit_or_balance, 200);
                }
            }
        }catch (\Exception $e){
            dd($e);
        }
    }

    /**
     * redirect the user to the paypal payment gateway..
     * @param $plan
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function purchase($plan){
        try{
                $package = SubscriptionPlans::query()->where('slug', '=', $plan)->firstOrFail();

                //get the adjusted amount if applicable it can be -ve, +ve or 0
                $adjusted_amount  = getAdjustedAmountForUpgradeDownGrade($package);
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
                        dd($adjusted_amount);
                        if($credits >= (-1 * $adjusted_amount['balance'])) {
                            $send_to_paypal = false;
                        } else {
                            $send_to_paypal = true;
                        }
                    }
                } else {
                    return redirect()->back()->with('error', $adjusted_amount['message']);
                }

                //need to create an entry to the user_subscription table...
                if (!is_null($package->validity)) {
                    //this means that the plan is trial plan
                    $expiry_date = Carbon::today()->addDays($package->validity)->format('Y-m-d');
                } else {
                    //will expires after 1 year
                    $expiry_date = Carbon::today()->addYear(1)->format('Y-m-d');
                }

                $renewal_date = Carbon::parse($expiry_date)->addDays(1)->format('Y-m-d');
                $user_subscription = UserSubscription::create([
                    'plan_id' => $package->id,
                    'user_id' => auth()->user()->id,
                    'paid_amount' => $package->price,
                    'subscription_expire_at' => $expiry_date,
                    'subscription_renewal_at' => $renewal_date,
                    'payment_status' => 'pending'
                ]);

                if ($package->price_plan_type == '1' && is_null($package->price)) {
                    $user_subscription->payment_status = "Completed";
                    $user_subscription->save();
                    return redirect()->back()->with('success', 'Your subscription plan has been activated.');
                } elseif ($package->price_plan_type == '1' && !is_null($package->price)) {
                    if(!$send_to_paypal){
                        //need to send the invoice directly to the user
                        $user_subscription->payment_status      = "Completed";
                        $user_subscription->discounted_amount   = round(($package->annual_discount / 100) * ($package->price * 12), 2);
                        $user_subscription->credits_used        = $credits;
                        $user_subscription->adjusted_amount     = $adjusted_amount['adjusted_amount'];
                        $user_subscription->save();

                        //update the credit_balance for the user
                        updateCreditBalanceForParent($adjusted_amount);
                        return redirect(route('plan.index'))->with('status', 'Congratulations! Your plan has been activated. We have also sent an invoice to your registered email.');
                    } else {
                        $link = generatePaypalExpressCheckoutLink($package, $user_subscription, route('plan.purchase.success'), route('plan.purchase.cancel'), null,$adjusted_amount);
                        return redirect($link);
                    }
                } else {
                    // the selected plan is enterprise plan and the user will have to communicate with the admin,,,,

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