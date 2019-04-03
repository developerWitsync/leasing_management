<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseSelectDiscountRate extends Model
{
    protected $table = 'lease_select_discount_rate';

    protected $fillable = [
        'asset_id',
        'lease_id',
        'interest_rate',
        'annual_average_esclation_rate',
        'discount_rate_to_use',
        'daily_discount_rate',
        'created_at',
        'updated_at'
    ];
}
