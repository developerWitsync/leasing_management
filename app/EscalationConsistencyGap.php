<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EscalationConsistencyGap extends Model
{
    protected $table = 'escalation_consistency_gap';

    protected $fillable = [
        'business_account_id',
        'years',
        'created_at',
        'updated_at'
    ];
}
