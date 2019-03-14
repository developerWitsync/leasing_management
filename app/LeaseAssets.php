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

    public function category(){
        return $this->belongsTo('App\LeaseAssetCategories', 'category_id', 'id');
    }

    public function subcategory(){
        return $this->belongsTo('App\LeaseAssetSubCategorySetting', 'sub_category_id', 'id');
    }

    public function lease(){
        return $this->belongsTo('App\Lease', 'lease_id', 'id');
    }

    public function payments(){
        return $this->hasMany('App\LeaseAssetPayments', 'asset_id', 'id');
    }

    public function fairMarketValue(){
        return $this->hasOne('App\FairMarketValue', 'asset_id','id');
    }

    public function terminationOption(){
        return $this->hasOne('App\LeaseTerminationOption', 'asset_id','id');
    }

    public function residualGuranteeValue(){
        return $this->hasOne('App\LeaseResidualValue', 'asset_id','id');
    }

    public function renewableOptionValue(){
        return $this->hasOne('App\LeaseRenewableOption', 'asset_id','id');
    }
    
    public function purchaseOption(){
        return $this->hasOne('App\PurchaseOption', 'asset_id','id');
    }

    public function leaseDurationClassified(){
        return $this->hasOne('App\LeaseDurationClassified', 'asset_id','id');
    }

    /**
     * Fetch the expected lease end date for a particular lease asset based upon all the conditions on the sheet NL9
     * @param LeaseAssets $asset
     * @return mixed
     */
    public function getLeaseEndDate(self $asset){
        if($asset->purchaseOption && $asset->purchaseOption->purchase_option_exerecisable == 'yes'){
            $lease_end_date =   $asset->purchaseOption->expected_lease_end_date;
        } else if($asset->renewableOptionValue && $asset->renewableOptionValue->is_reasonable_certainity_option == 'yes'){
            $lease_end_date =   $asset->renewableOptionValue->expected_lease_end_Date;
        } else if($asset->terminationOption && $asset->terminationOption->exercise_termination_option_available == 'yes'){
            $lease_end_date =   $asset->terminationOption->lease_end_date;
        } else {
            $lease_end_date = $asset->lease_end_date;
        }
        return $lease_end_date;
    }

    public function leaseSelectLowValue(){
        return $this->hasOne('App\LeaseSelectLowValue', 'asset_id','id');

    }

    public function leaseSelectDiscountRate(){
        return $this->hasOne('App\LeaseSelectDiscountRate', 'asset_id','id');

    }

    public function leaseBalanceAsOnDec(){
        return $this->hasOne('App\LeaseBalanceAsOnDec', 'asset_id','id');

    }

    public function leaseIncentives(){
        return $this->hasOne('App\LeaseIncentives', 'asset_id','id');
    }
    
    public function initialDirectCost(){
        return $this->hasOne('App\InitialDirectCost', 'asset_id','id');
    }

    public function leaseIncentiveCost(){
        return $this->hasOne('App\LeaseIncentives', 'asset_id','id');
    }


    public function categoriesleaseassetexcluded(){
        return $this->hasOne('App\CategoriesLeaseAssetExcluded', 'asset_id','id');
    }

    public function country(){
        return $this->belongsTo('App\Countries', 'country_id', 'id');
    }

    public function specificUse(){
        return $this->belongsTo('App\UseOfLeaseAsset', 'specific_use', 'id');
    }

    public function expectedLife(){
        return $this->belongsTo('App\ExpectedLifeOfAsset', 'expected_life', 'id');
    }

    /**
     * calculate the Present Value of Lease Liability
     * @param bool $return_value
     * @return array|int
     */
    public function presentValueOfLeaseLiability($return_value = false){
        $start_date =   Carbon::parse($this->accural_period);
//        $base_date = Carbon::create(2019, 01, 01);
        $base_date = Carbon::parse(getParentDetails()->accountingStandard->base_date);


        $base_date = ($start_date->lessThan($base_date))?$base_date:$start_date;
        $end_date = Carbon::parse($this->getLeaseEndDate($this));

        $start_year = $base_date->format('Y');
        $end_year = $end_date->format('Y');
        $first_month = $base_date->format('m');

        if($start_year == $end_year) {
            $years[] = $end_year;
        } else if($end_year > $start_year) {
            $years = range($start_year, $end_year);
        }

        $months = [];
        for($m=1; $m<=12; ++$m ){
            $months[$m] = date('M', mktime(0, 0, 0, $m, 1));
        }

        $present_value_of_lease_liability = [];

        $total_lease_liability = 0;

        $first_year = $start_year;

        while ($start_year <= $end_year) {
            foreach ($months as $key=>$month){
                $k_m = sprintf("%02d", $key);
                if(($first_year == $start_year && $first_month <= $k_m) || ($first_year < $start_year)){
                    //need to call a procedure from here that can return the value of the lease liablity for all the payments of the asset
                    foreach ($this->payments as $payment_key=>$payment){
//                    dd("call present_value_of_lease_liability('{$start_year}', '{$k_m}', '{$base_date}', '{$this->id}', '{$payment->id}')");
                        $data = DB::select('call present_value_of_lease_liability(?, ?, ?, ?, ?)',[$start_year, $k_m, $base_date, $this->id, $payment->id]);
                        if(count($data) > 0){
                            $total_lease_liability = $total_lease_liability + $data[0]->lease_liability;
                            $present_value_of_lease_liability[$start_year][$month]["payment_".$payment->id] = $data;
                        }
                    }
                }

            }
            $start_year = $start_year + 1;
        }

        if($return_value) {
            return $total_lease_liability;
        } else {
            return ['present_value_data' => $present_value_of_lease_liability, 'years' => $years, 'months' => $months];
        }
    }

     public function paymentsduedate(){
        return $this->hasMany('App\LeaseAssetPaymenetDueDate', 'asset_id', 'id');
    }
    
}
