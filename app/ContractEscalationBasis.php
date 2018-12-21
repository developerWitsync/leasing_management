<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractEscalationBasis extends Model
{
    protected  $table = 'contract_escalation_basis';
    protected $fillable = [
        'title',
        'status',
        'created_at',
        'updated_at'
    ];
}
