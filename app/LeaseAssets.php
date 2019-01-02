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
}
