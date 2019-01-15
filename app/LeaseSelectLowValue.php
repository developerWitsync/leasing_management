<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseSelectLowValue extends Model
{
    protected $table = 'lease_select_low_value';

    protected $fillable = [
        'asset_id',
        'lease_id',
        'fair_market_value',
        'undiscounted_lease_payment',
        'is_classify_under_low_value',
        'reason',
        'created_at',
        'updated_at'
    ];
}
