<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetPaymenetDueDate extends Model
{
    protected $table = 'lease_asset_payment_dates';

    protected $fillable = [
        'asset_id',
        'payment_id',
        'date',
        'total_payment_amount',
        'created_at',
        'updated_at'
    ];
}
