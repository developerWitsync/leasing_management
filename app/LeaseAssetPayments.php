<?php

namespace App;

use Carbon\Carbon;
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
        'lease_payment_per_interval',
        'subsequent_status',
        'immediate_previous_lease_end_date',
        'last_lease_payment_interval_end_date'
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

    /**
     * calculates the last Payment Interval end date
     * considering that this date cannot go after the lease end date in case the date is getting after the lease end date we have to consider the lease end date
     * @param $last_payment_date
     * @param $lease_end_date
     * @return Carbon
     */
    public function lastPaymentIntevalEndDate($last_payment_date, $lease_end_date){
        if($this->payout_time == 1) {
            //at interval start we will have to add the interval to the last payment date
            switch ($this->payment_interval){
                case 1 :
                    // one-time payment will return the lease end date from here..
                    $new_date = $lease_end_date;
                    break;
                case 2 :
                    //this is the monthly payment and have to add 1 month to the last payment date
                    $new_date = Carbon::parse($last_payment_date)->addMonth(1);
                    break;
                case 3 :
                    $new_date = Carbon::parse($last_payment_date)->addMonth(3);
                    break;
                case 4 :
                    $new_date = Carbon::parse($last_payment_date)->addMonth(6);
                    break;
                case 5 :
                    $new_date = Carbon::parse($last_payment_date)->addMonth(12);
                    break;
                case 6 :
                    $new_date = $last_payment_date;
                    break;
            }

            if($new_date->greaterThanOrEqualTo($lease_end_date)){
                $return = $lease_end_date;
            } else {
                $return = $new_date;
            }
            return $return;
        } else {
            //at interval end, we will just return the last payment date
            return $last_payment_date;
        }
    }
}
