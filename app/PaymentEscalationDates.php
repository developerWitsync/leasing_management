<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentEscalationDates extends Model
{
    protected $table = 'payment_escalation_dates';

    protected $fillable = [
        'payment_id',
        'escalation_year',
        'escalation_month',
        'percentage_or_amount_based',
        'value_escalated',
        'total_amount_payable',
        'created_at',
        'updated_at'
    ];
}
