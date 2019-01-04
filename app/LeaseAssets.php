<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'total_payments',
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
    public function initialDirectCost(){
        return $this->hasOne('App\InitialDirectCost', 'asset_id','id');

    }
}
