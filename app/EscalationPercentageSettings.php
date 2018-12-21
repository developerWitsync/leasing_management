<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EscalationPercentageSettings extends Model
{
    protected $table = 'escalation_percentage_settings';

    protected $fillable = [
        'business_account_id',
        'number',
        'status',
        'created_at',
        'updated_at'
    ];
}
