<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterestAndDepreciation extends Model
{
    protected $table = 'interest_and_depreciation';

    protected $fillable = [
        'asset_id',
        'modify_id',
        'date',
        'number_of_days',
        'opening_lease_liability',
        'interest_expense',
        'lease_payment',
        'discount_rate',
        'closing_lease_liability',
        'value_of_lease_asset',
        'depreciation',
        'accumulated_depreciation',
        'carrying_value_of_lease_asset',
        'change',
        'created_at',
        'updated_at',
        'charge_to_pl'
    ];
}
