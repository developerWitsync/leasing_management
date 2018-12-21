<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EscalationAmountCalculated extends Model
{
    protected $table = 'escalation_amount_calculated_on';

    protected $fillable = ['title', 'status', 'created_at', 'updated_at'];
}
