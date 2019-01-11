<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentEscalationInconsistentData extends Model
{
    protected $table = 'payment_escalation_inconsitent_inputs';

    protected $fillable = [
        'payment_id',
        'inconsistent_data',
        'created_at',
        'updated_at'
    ];

}
