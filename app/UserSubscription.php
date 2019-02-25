<?php

namespace App;

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
        'discounted_amount',
        'created_at',
        'updated_at'
    ];

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function subscriptionPackage(){
        return $this->hasOne('App\SubscriptionPlans', 'id', 'plan_id');
    }
}
