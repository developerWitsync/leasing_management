<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentEscalationDetails extends Model
{

    protected $table = 'payment_escalation';

    protected $fillable = [
        'lease_id',
        'asset_id',
        'payment_id',
        'is_escalation_applicable',
        'effective_from',
        'escalation_basis',
        'escalation_rate_type',
        'is_escalation_applied_annually_consistently',
        'fixed_rate',
        'current_variable_rate',
        'total_escalation_rate',
        'amount_based_currency',
        'escalated_amount',
        'escalation_currency',
        'total_undiscounted_lease_payment_amount'
    ];

    public function escalationBasis(){
        return $this->hasOne('App\ContractEscalationBasis', 'id', 'escalation_basis');
    }
}
