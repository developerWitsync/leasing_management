<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentEscalationDetails extends Model
{

    protected $fillable = [
        'lease_id',
        'asset_id',
        'payment_id',
        'is_escalation_applicable',
        'effective_from'
    ];
}
