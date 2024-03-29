<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetPaymentDates extends Model
{
    protected $table = 'lease_asset_payment_dates';

    protected $fillable = [
        'id',
        'asset_id',
        'payment_id',
        'date',
        'total_payment_amount',
        'created_at',
        'updated_at'
    ];

   
}
