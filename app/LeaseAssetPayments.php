<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetPayments extends Model
{
    protected $table = 'lease_assets_payments';

    protected $appends = ['undiscounted_liability_value'];

    protected $fillable = [
        'asset_id',
        'name',
        'type',
        'nature',
        'variable_basis',
        'variable_amount_determinable',
        'description',
        'payment_interval',
        'payout_time',
        'first_payment_start_date',
        'last_payment_end_date',
        'payment_currency',
        'payment_per_interval_per_unit',
        'total_amount_per_interval',
        'attachment',
        'created_at',
        'updated_at',
        'lease_payment_per_interval'
    ];

    public function getUndiscountedLiabilityValueAttribute()
    {
        return $this->getUndiscountedValue();
    }

    public function category(){
        return $this->belongsTo('App\LeasePaymentComponents', 'type', 'id');
    }

    public function asset(){
        return $this->belongsTo('App\LeaseAssets', 'asset_id', 'id');
    }

    public function paymentNature(){
        return $this->belongsTo('App\LeaseAssetPaymentsNature', 'nature', 'id');
    }

    public function paymentInterval(){
        return $this->belongsTo('App\LeasePaymentsInterval', 'payout_time', 'id');
    }

    public function paymentType(){
        return $this->belongsTo('App\LeasePaymentComponents', 'type', 'id');
    }

    public function paymentFrequency(){
        return $this->belongsTo('App\LeasePaymentsFrequency', 'payment_interval', 'id');
    }

    public function paymentDueDates(){
        return $this->hasMany('App\LeaseAssetPaymenetDueDate', 'payment_id', 'id')->orderBy('date', 'asc');
    }

    public function paymentEscalations(){
        return $this->hasMany('App\PaymentEscalationDetails', 'payment_id', 'id');
    }

    public function paymentEscalationSingle(){
        return $this->hasOne('App\PaymentEscalationDetails', 'payment_id', 'id');
    }

    public function paymentEscalationDates(){
        return $this->hasMany('App\PaymentEscalationDates', 'payment_id', 'id');
    }

    /**
     * get the undiscounted lease value for the current payment...
     * @return mixed
     */
    public function getUndiscountedValue(){
        $lease = Lease::query()->findOrFail($this->asset->lease_id);
        if($lease->escalation_clause_applicable == "no"){
            //will have to get the total from the paymentDueDates
            return $this->calculateUndiscountedValueForPaymentDueDates();
        } else {
            //in case of yes have to check if the escalation is applicable
            if($this->paymentEscalationSingle && $this->paymentEscalationSingle->is_escalation_applicable == "yes"){
                return $this->calculateUndiscountedValueForEscalations();
            } else {
                //will have to get the total from the payment due dates only..
                return $this->calculateUndiscountedValueForPaymentDueDates();
            }
        }
    }

    /**
     * total of the total amount payable from the escalation dates...
     * @return mixed
     */
    protected function calculateUndiscountedValueForEscalations(){
        return $this->paymentEscalationDates->sum('total_amount_payable');
    }

    /**
     * Sum of the total_amount from the total payments due dates...
     * @return mixed
     */
    protected function calculateUndiscountedValueForPaymentDueDates(){
        return $this->paymentDueDates->sum('total_payment_amount');
    }
}
