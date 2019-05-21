<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricalCarryingAmountAnnexure extends Model
{
    protected $table = 'historical_carrying_amount_annexure';

    protected $fillable = [
        'asset_id',
        'days_diff',
        'year',
        'payment_amount',
        'present_value_of_lease_payment',
        'historical_present_value_of_lease_payment',
        'historical_depreciation',
        'historical_accumulated_depreciation',
        'carrying_value_of_lease_asset'
    ];
}
