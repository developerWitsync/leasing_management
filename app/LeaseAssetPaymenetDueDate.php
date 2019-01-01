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
        'created_at',
        'updated_at'
    ];
}
