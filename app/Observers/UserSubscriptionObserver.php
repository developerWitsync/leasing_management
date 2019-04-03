<?php
/**
 * Created by PhpStorm.
 * User: himanshur
 * Date: 27/3/19
 * Time: 1:21 PM
 */

namespace App\Observers;


use App\UserSubscription;

class UserSubscriptionObserver
{

    /**
     * Listen to the created event so that we can update the invoice number as well..
     * @param UserSubscription $subscription
     */
    public function created(UserSubscription $subscription)
    {
        //generate the invoice number for the user_subscription and update the created user_subscription package...
        $subscription->setAttribute('invoice_number', generateInvoiceNumber($subscription));
        $subscription->save();
    }
}