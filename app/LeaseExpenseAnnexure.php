<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseExpenseAnnexure extends Model
{
    protected $table = 'lease_expense_annexure';

    protected $casts = [
        'payments_details' => 'array'
    ];

    protected $fillable = [
        'asset_id',
        'date',
        'opening_prepaid_payable_balance',
        'total_computed_lease_expense',
        'closing_prepaid_payable_balance',
        'payments_details'
    ];
}
