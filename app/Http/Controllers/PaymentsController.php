<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 19/2/19
 * Time: 6:20 PM
 */

namespace App\Http\Controllers;


use App\SubscriptionPayments;

class PaymentsController extends Controller
{
    public function index($payment_id){
        try{
            $payment = SubscriptionPayments::query()->findOrFail($payment_id);
            return view('payment.index', compact(
                'payment'
            ));
        } catch (\Exception $e){
            abort(404);
        }
    }
}