<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerIncentives extends Model
{
    protected $table = 'customer_incentives';

    protected $fillable = [
        'customer_name',
        'description',
        'currency_id',
        'amount',
        'created_at',
        'updated_at'
    ];
}
