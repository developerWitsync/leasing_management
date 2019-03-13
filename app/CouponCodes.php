<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponCodes extends Model
{
    protected $table = 'coupon_codes';

    protected $fillable = [
        'code',
        'status',
        'no_of_uses',
        'user_id',
        'plan_id',
        'discount'
    ];

    public function user(){
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function plan(){
        return $this->hasOne('App\SubscriptionPlans', 'id', 'plan_id');
    }
}
