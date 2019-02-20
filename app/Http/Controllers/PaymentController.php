<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 20/2/19
 * Time: 3:27 PM
 */

namespace App\Http\Controllers;

use App\User;
use App\UserSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\ExpressCheckout;
use Mail;
use Storage;
use App\Mail\RegistrationCredentials;
use App\Mail\RegistrationConfirmation;

class PaymentController extends Controller
{
    protected $provider;
    public function __construct() {
        $this->provider = new ExpressCheckout();
    }

    /**
     * update the subscription package details for the user
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
                $response = $this->provider->createMonthlySubscription($response['TOKEN'], $response['AMT'], $cart['subscription_desc']);
                $status = 'Invalid';
                // if after creating the subscription paypal responds with activeprofile or pendingprofile
                // we are good to go and we can set the status to Processed, else status stays Invalid
                if (!empty($response['PROFILESTATUS']) && in_array($response['PROFILESTATUS'], ['ActiveProfile', 'PendingProfile'])) {
                    $status = 'Processed';
                }
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
                //send confirmation email from here
                Mail::to($subscription->user)->queue(new RegistrationConfirmation($subscription->user));
                //need to send the user credentials email to the user
                Mail::to($subscription->user)->queue(new RegistrationCredentials($subscription->user, $subscription->subscriptionPackage, $subscription));
                return redirect('/login')->with('success', 'Your account has been registered. Please check your email inbox to proceed further.');
            }
            return redirect('/')->with('error', 'Error processing PayPal payment for Order ' . $subscription->id . '!');
        }catch (\Exception $e){
            abort(404, $e->getMessage());
        }
    }

    /**
     * cancel request when generated from paypal...
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request){
        try{
            $token = $request->get('token');
            // to get the payment details from paypal
            $response = $this->provider->getExpressCheckoutDetails($token);
            if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
                return redirect('/')->with('error','Error processing PayPal payment.');
            }
            // invoice id is stored in INVNUM which will be the id of the invoice
            $invoice_id = $response['INVNUM'];
            $subscription = UserSubscription::query()->find($invoice_id);

            $subscription->payment_status = 'Cancelled';
            $subscription->save();
            //send confirmation email from here
            Mail::to($subscription->user)->queue(new RegistrationConfirmation($subscription->user));
            //need to send the user credentials email to the user
            Mail::to($subscription->user)->queue(new RegistrationCredentials($subscription->user, $subscription->subscriptionPackage, $subscription));
            return redirect('/login')->with('success', 'Your account has been registered. Please check your email inbox to proceed further.');
        } catch (\Exception $e){
            abort(404, $e->getMessage());
        }
    }

    /**
     * update the user_subscription with the status that has been returned from the IPN.....
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notify(Request $request){
        try{
            Log::info("IPN". json_encode($request->all()));
            $invoice_id = $request->input('invoice', false);
            if($invoice_id){
                $subscription = UserSubscription::query()->find($invoice_id);
                $subscription->payment_status = $request->payment_status;
                $subscription->save();

                //send confirmation email from here
                Mail::to($subscription->user)->queue(new RegistrationConfirmation($subscription->user));
                //need to send the user credentials email to the user
                Mail::to($subscription->user)->queue(new RegistrationCredentials($subscription->user, $subscription->subscriptionPackage, $subscription));
            }
            return response()->json(['status'=>true], 200);
        } catch (\Exception $e){
            abort(404, $e->getMessage());
        }
    }
}