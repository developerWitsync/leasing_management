<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeasePaymentsEscalationClause extends Model
{
    protected $table = 'lease_payments_escalation_clause';
    protected $fillable = ['title', 'status', 'created_at', 'updated_at'];
}
