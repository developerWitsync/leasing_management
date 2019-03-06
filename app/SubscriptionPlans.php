<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlans extends Model
{
    protected $table = 'subscription_plans';

    protected $fillable = [
        'title',
        'slug',
        'price',
        'available_leases',
        'available_users',
        'hosting_type',
        'validity',
        'email',
        'is_custom',
        'most_popular',
        'created_at',
        'updated_at',
        'annual_discount'
    ];
}
