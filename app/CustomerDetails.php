<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerDetails extends Model
{
    protected $table = 'customer_details';

    protected $fillable = [
        'lease_incentive_id',
        'customer_name',
        'description',
        'incentive_date',
        'currency_id',
        'amount',
        'exchange_rate',
        'created_at',
        'updated_at'
    ];
}
