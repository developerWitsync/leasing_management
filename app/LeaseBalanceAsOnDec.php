<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseBalanceAsOnDec extends Model
{
    protected $table = 'lease_balance_as_on_dec';

    protected $fillable = [
        'asset_id',
        'lease_id',
        'reporting_currency',
        'exchange_rate',
        'reporting_currency_selected',
        'carrying_amount',
        'liability_balance',
        'prepaid_lease_payment_balance',
        'accrued_lease_payment_balance',
        'outstanding_lease_payment_balance',
        'any_provision_for_onerous_lease',
        'created_at',
        'updated_at'
    ];
}
