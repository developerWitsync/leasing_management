<?php

namespace App;

use App\Observers\UserSubscriptionObserver;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $table = 'user_subscription';

    protected $fillable = [
        'plan_id',
        'user_id',
        'payment_method',
        'paid_amount',
        'geteway_transasction_id',
        'subscription_expire_at',
        'subscription_renewal_at',
        'payment_status',
        'purchased_items',
        'subscription_years',
        'discounted_amount',
        'adjusted_amount',
        'credits_used',
        'coupon_code',
        'coupon_discount',
        'created_at',
        'updated_at',
        'invoice_number'
    ];

    protected $dispatchesEvents = [
        'created' => UserSubscriptionObserver::class
    ];

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function subscriptionPackage(){
        return $this->hasOne('App\SubscriptionPlans', 'id', 'plan_id');
    }

    public function coupon(){
        return $this->hasOne('App\CouponCodes', 'code', 'coupon_code');
    }
}
