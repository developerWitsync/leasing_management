<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use DB;

class LeaseAssets extends Model
{
    protected $table = 'lease_assets';

    protected $fillable = [
        'lease_id',
        'uuid',
        'category_id',
        'sub_category_id',
        'name',
        'other_details',
        'country_id',
        'location',
        'specific_use',
        'use_of_asset',
        'expected_life',
        'lease_start_date',
        'lease_free_period',
        'accural_period',
        'lease_end_date',
        'lease_term',
        'accounting_treatment',
        'similar_asset_items',
        'is_details_completed',
        'using_lease_payment',
        'total_payments',
        'lease_liablity_value',
        'value_of_lease_asset',
        'impairment_test_value',
        'created_at',
        'updated_at'
    ];

    public function accountingTreatment(){
        return $this->belongsTo('App\LeaseAccountingTreatment', 'accounting_treatment', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\LeaseAssetCategories', 'category_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo('App\LeaseAssetSubCategorySetting', 'sub_category_id', 'id');
    }

    public function lease()
    {
        return $this->belongsTo('App\Lease', 'lease_id', 'id');
    }

    public function payments()
    {
        return $this->hasMany('App\LeaseAssetPayments', 'asset_id', 'id');
    }

    public function fairMarketValue()
    {
        return $this->hasOne('App\FairMarketValue', 'asset_id', 'id');
    }

    public function terminationOption()
    {
        return $this->hasOne('App\LeaseTerminationOption', 'asset_id', 'id');
    }

    public function residualGuranteeValue()
    {
        return $this->hasOne('App\LeaseResidualValue', 'asset_id', 'id');
    }

    public function renewableOptionValue()
    {
        return $this->hasOne('App\LeaseRenewableOption', 'asset_id', 'id');
    }

    public function purchaseOption()
    {
        return $this->hasOne('App\PurchaseOption', 'asset_id', 'id');
    }

    public function leaseDurationClassified()
    {
        return $this->hasOne('App\LeaseDurationClassified', 'asset_id', 'id');
    }

    /**
     * Fetch the expected lease end date for a particular lease asset based upon all the conditions on the sheet NL9
     * @param LeaseAssets $asset
     * @return mixed
     */
    public function getLeaseEndDate(self $asset)
    {
        if ($asset->purchaseOption && $asset->purchaseOption->purchase_option_exerecisable == 'yes') {
            $lease_end_date = $asset->purchaseOption->expected_lease_end_date;
        } else if ($asset->renewableOptionValue && $asset->renewableOptionValue->is_reasonable_certainity_option == 'yes') {
            $lease_end_date = $asset->renewableOptionValue->expected_lease_end_Date;
        } else if ($asset->terminationOption && $asset->terminationOption->exercise_termination_option_available == 'yes') {
            $lease_end_date = $asset->terminationOption->lease_end_date;
        } else {
            $lease_end_date = $asset->lease_end_date;
        }
        return $lease_end_date;
    }

    public function leaseSelectLowValue()
    {
        return $this->hasOne('App\LeaseSelectLowValue', 'asset_id', 'id');

    }

    public function leaseSelectDiscountRate()
    {
        return $this->hasOne('App\LeaseSelectDiscountRate', 'asset_id', 'id');

    }

    public function leaseBalanceAsOnDec()
    {
        return $this->hasOne('App\LeaseBalanceAsOnDec', 'asset_id', 'id');

    }

    public function leaseIncentives()
    {
        return $this->hasOne('App\LeaseIncentives', 'asset_id', 'id');
    }

    public function initialDirectCost()
    {
        return $this->hasOne('App\InitialDirectCost', 'asset_id', 'id');
    }

    public function dismantlingCost()
    {
        return $this->hasOne('App\DismantlingCosts', 'asset_id', 'id');
    }

    public function securityDeposit()
    {
        return $this->hasOne('App\SecurityDeposits', 'asset_id', 'id');
    }

    public function leaseIncentiveCost()
    {
        return $this->hasOne('App\LeaseIncentives', 'asset_id', 'id');
    }


    public function categoriesleaseassetexcluded()
    {
        return $this->hasOne('App\CategoriesLeaseAssetExcluded', 'asset_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo('App\Countries', 'country_id', 'id');
    }

    public function specificUse()
    {
        return $this->belongsTo('App\UseOfLeaseAsset', 'specific_use', 'id');
    }

    public function expectedLife()
    {
        return $this->belongsTo('App\ExpectedLifeOfAsset', 'expected_life', 'id');
    }

    /**
     * fetch the pv calculus calculations from the views...
     * @param $base_date
     * @param $asset_id
     * @param $payment_id
     * @param $start_year
     * @param string $escalation_applied
     * @return mixed
     */
    public function fetchPVCalculus($base_date, $asset_id,$payment_id, $start_year, $escalation_applied = "yes"){
        if($escalation_applied == "yes"){
            $sql = "SELECT 
                        `payment_date`,
                        `payment_id`,
                        `payment_name`,
                        `total_amount_payable`,
                        `daily_discount_rate` as `discount_rate`,
                        ROUND(`total_amount_payable` / POWER( 1 + (`daily_discount_rate`) * (1 / 100), datediff(`payment_date`, '{$base_date}')), 2) as lease_liability,
                        `year` as `payment_year`,
                        DATE_FORMAT(`payment_date`, '%b') AS `payment_month`,
                        'yes' as `is_escalation_applicable`,
                        datediff(`payment_date`, '{$base_date}') as `days_diff`
                    FROM
                        pv_calculus_view_when_escalation_applied
                    WHERE
                        `year` >= '{$start_year}'
                        and `asset_id` = '{$asset_id}'
                        and `payment_id` = '{$payment_id}'";
        } else {
            $sql = "SELECT 
                        `payment_date`,
                        `payment_id`,
                        `payment_name`,
                        `total_amount_payable`,
                        `daily_discount_rate` as `discount_rate`,
                        ROUND(`total_amount_payable` / POWER( 1 + (`daily_discount_rate`) * (1 / 100), datediff(`payment_date`, '{$base_date}')), 2) as lease_liability,
                        `year` as `payment_year`,
                        DATE_FORMAT(`payment_date`, '%b') AS `payment_month`,
                        'no' as `is_escalation_applicable`,
                        datediff(`payment_date`, '{$base_date}') as `days_diff`
                    FROM
                        pv_calculus_view_when_escalation_not_applied
                    WHERE
                        `year` >= '{$start_year}'
                        and `asset_id` = '{$asset_id}'
                        and `payment_id` = '{$payment_id}'";
        }

        $results = DB::select($sql);
        return $results;
    }

    /**
     * calculate the Present Value of Lease Liability
     * @param bool $return_value
     * @param null $payment_id
     * @return array|int
     */
    public function presentValueOfLeaseLiability($return_value = false, $payment_id = null)
    {

        $lease = $this->lease;


        $start_date = Carbon::parse($this->accural_period);
        $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);

        $subsequent_modify_required = $lease->isSubsequentModification();
        if($subsequent_modify_required) {
            $base_date = Carbon::parse($lease->modifyLeaseApplication->last()->effective_from);
            $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
        } else {
            $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);
            $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
        }

        $base_date = ($start_date->lessThan($base_date)) ? $base_date : $start_date;
        $end_date = Carbon::parse($this->getLeaseEndDate($this));

        $start_year = $base_date->format('Y');
        $end_year = $end_date->format('Y');
        $first_month = $base_date->format('m');

        if ($start_year == $end_year) {
            $years[] = $end_year;
        } else if ($end_year > $start_year) {
            $years = range($start_year, $end_year);
        }

        $months = [];
        for ($m = 1; $m <= 12; ++$m) {
            $months[$m] = date('M', mktime(0, 0, 0, $m, 1));
        }

        $present_value_of_lease_liability =  [];

        $total_lease_liability = 0;

        $first_year = $start_year;

        if ($payment_id) {
            $payments = LeaseAssetPayments::query()->where('id', '=', $payment_id)->get();
        } else {
            $payments = $this->payments;
        }


//        while ($start_year <= $end_year) {
//            foreach ($months as $key=>$month){
//                $k_m = sprintf("%02d", $key);
//                if(($first_year == $start_year && $first_month <= $k_m) || ($first_year < $start_year)){
//                    //need to call a procedure from here that can return the value of the lease liablity for all the payments of the asset
//                    foreach ($payments as $payment_key=>$payment){
//                        $data = DB::select('call present_value_of_lease_liability(?, ?, ?, ?, ?)',[$start_year, $k_m, $base_date, $this->id, $payment->id]);
//                        if(count($data) > 0){
//                            $total_lease_liability = $total_lease_liability + $data[0]->lease_liability;
//                            $present_value_of_lease_liability[$start_year][$month]["payment_".$payment->id] = $data;
//                        }
//                    }
//                }
//
//            }
//            $start_year = $start_year + 1;
//        }

        //check if the escalations are applied or not and on the basis of the same fetch the data from the view..
        foreach ($payments as $payment_key => $payment) {
            $is_eslaction_applicable = PaymentEscalationDetails::query()
                ->select('is_escalation_applicable')
                ->where('asset_id', '=', $this->id)
                ->where('payment_id', '=', $payment->id)
                ->first();

            if ($is_eslaction_applicable && $is_eslaction_applicable->is_escalation_applicable == "yes") {
                //call the procedure `pv_calculus_when_escalation_is_applied`

                $results = $this->fetchPVCalculus($base_date, $this->id, $payment->id,$start_year, 'yes');

                if (count($results) > 0) {
                    foreach ($results as $result) {
                        $total_lease_liability = $total_lease_liability + $result->lease_liability;
                        $present_value_of_lease_liability[$result->payment_year][$result->payment_month]["payment_" . $result->payment_id] = $result;
                    }
                }
            } elseif(is_null($is_eslaction_applicable) || ($is_eslaction_applicable && $is_eslaction_applicable->is_escalation_applicable == "no")) {
                //call the procedure `pv_calculus_when_escalation_is_not_applied`
                $results = $this->fetchPVCalculus($base_date, $this->id,$payment->id,$start_year, 'no');

                if (count($results) > 0) {
                    foreach ($results as $result) {
                        $total_lease_liability = $total_lease_liability + $result->lease_liability;
                        $present_value_of_lease_liability[$result->payment_year][$result->payment_month]["payment_" . $result->payment_id] = $result;
                    }
                }
            }

            //save the present value for the payment here....
            $payment->setAttribute('present_value', $total_lease_liability);
            $payment->save();

        }

        if (is_null($payment_id)) {

            //get the present value for the termination option if applicable..
            $termination_present_value = $this->getLeaseLiabilityForTermination($base_date, $total_lease_liability, $present_value_of_lease_liability);
            $present_value_of_lease_liability = $termination_present_value['present_value_of_lease_liability'];
            $total_lease_liability = $termination_present_value['total_lease_liability'];

            //get the present value for the residual value guarantee if applicable..
            $residual_present_value = $this->getPresentValueOfResidualValueGuarantee($base_date, $total_lease_liability, $present_value_of_lease_liability, $end_date);
            $present_value_of_lease_liability = $residual_present_value['present_value_of_lease_liability'];
            $total_lease_liability = $residual_present_value['total_lease_liability'];

            //get the present value for the purchase option if applicable...
            $purchase_present_value = $this->getPresentValueOfPurchaseOption($base_date, $total_lease_liability, $present_value_of_lease_liability);
            $present_value_of_lease_liability = $purchase_present_value['present_value_of_lease_liability'];
            $total_lease_liability = $purchase_present_value['total_lease_liability'];
        }

        if ($return_value) {
            return $total_lease_liability;
        } else {
            return ['present_value_data' => $present_value_of_lease_liability, 'years' => $years, 'months' => $months];
        }
    }

    /**
     * termination option present value for the the current asset...
     * @param $base_date
     * @param int $total_lease_liability
     * @param array $present_value_of_lease_liability
     * @return array
     */
    public function getLeaseLiabilityForTermination($base_date, $total_lease_liability = 0, $present_value_of_lease_liability = [])
    {
        //have to check for the lease termination penalty option and for the lease residual value guarantee option as well and have to calculate the present value
        //based upon the same formulae
        if ($this->terminationOption->lease_termination_option_available == "yes" && $this->terminationOption->exercise_termination_option_available == "yes" && $this->terminationOption->termination_penalty_applicable == "yes") {
            $termination_year = Carbon::parse($this->terminationOption->lease_end_date)->format('Y');
            $termination_month = Carbon::parse($this->terminationOption->lease_end_date)->format('M');

            $data = DB::select('call present_value_of_lease_liability_termination(?, ?, ?)', [$this->terminationOption->id, $this->id, $base_date]);
            if (count($data) > 0) {
                $total_lease_liability = $total_lease_liability + $data[0]->lease_liability;
                $present_value_of_lease_liability[$termination_year][$termination_month]["termination"] = $data;
            }
        }

        return ['present_value_of_lease_liability' => $present_value_of_lease_liability, 'total_lease_liability' => $total_lease_liability];
    }

    /**
     * Residual Value Guarantee Present Value for the current asset....
     * @param $base_date
     * @param int $total_lease_liability
     * @param array $present_value_of_lease_liability
     * @param null $end_date
     * @return array
     */
    public function getPresentValueOfResidualValueGuarantee($base_date, $total_lease_liability = 0, $present_value_of_lease_liability = [], $end_date = null)
    {
        //check if the residual value guarantee is applicable or not
        if ($this->residualGuranteeValue->any_residual_value_gurantee == "yes") {
            $residual_year = Carbon::parse($end_date)->format('Y');
            $residual_month = Carbon::parse($end_date)->format('M');

            $data = DB::select('call present_value_of_lease_liability_residual(?, ?, ?, ?)', [$this->residualGuranteeValue->id, $this->id, $end_date, $base_date]);
            if (count($data) > 0) {
                $total_lease_liability = $total_lease_liability + $data[0]->lease_liability;
                $present_value_of_lease_liability[$residual_year][$residual_month]["residual"] = $data;
            }
        }
        return ['present_value_of_lease_liability' => $present_value_of_lease_liability, 'total_lease_liability' => $total_lease_liability];
    }

    /**
     * Purchase Option Present value for the current asset...
     * @param $base_date
     * @param int $total_lease_liability
     * @param array $present_value_of_lease_liability
     * @return array
     */
    public function getPresentValueOfPurchaseOption($base_date, $total_lease_liability = 0, $present_value_of_lease_liability = [])
    {
        if ($this->purchaseOption && $this->purchaseOption->purchase_option_clause == "yes" && $this->purchaseOption->purchase_option_exerecisable == "yes") {
            $purchase_year = Carbon::parse($this->purchaseOption->expected_purchase_date)->format('Y');
            $purchase_month = Carbon::parse($this->purchaseOption->expected_purchase_date)->format('M');
            $data = DB::select('call present_value_of_lease_liability_purchase(?, ?, ?)', [$this->purchaseOption->id, $this->id, $base_date]);
            if (count($data) > 0) {
                $total_lease_liability = $total_lease_liability + $data[0]->lease_liability;
                $present_value_of_lease_liability[$purchase_year][$purchase_month]["purchase"] = $data;
            }
        }
        return ['present_value_of_lease_liability' => $present_value_of_lease_liability, 'total_lease_liability' => $total_lease_liability];
    }

    /**
     * undiscounted Lease liability for the asset...
     * @param $id
     * @return int
     */
    public function getUndiscountedLeaseLiability($id){
        $asset = self::query()->findOrFail($id);
        $payments = $asset->payments;
        $total = 0;

        foreach($payments as $payment){
            $total = $total + $payment->getUndiscountedValue();
        }

        if($asset->terminationOption->lease_termination_option_available == "yes" && $asset->terminationOption->exercise_termination_option_available == "yes" && $asset->terminationOption->termination_penalty_applicable == "yes"){
            $total = $total + $asset->terminationOption->termination_penalty;
        }

        if($asset->residualGuranteeValue->any_residual_value_gurantee == "yes"){
            $total = $total + $asset->residualGuranteeValue->residual_gurantee_value;
        }

        if($asset->purchaseOption && $asset->purchaseOption->purchase_option_clause == "yes" && $asset->purchaseOption->purchase_option_exerecisable == "yes"){
            $total = $total + $asset->purchaseOption->purchase_price;
        }

        return $total;
    }

    public function paymentsduedate()
    {
        return $this->hasMany('App\LeaseAssetPaymenetDueDate', 'asset_id', 'id');
    }

}
