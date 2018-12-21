<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetSubCategorySetting extends Model
{
    protected $table = 'lease_assets_sub_categories_settings';
    protected $fillable = [
        'business_account_id',
        'category_id',
        'depreciation_method_id',
        'title',
        'created_at',
        'updated_at'
    ];

    public function depreciationMethod(){
        return $this->belongsTo('App\DepreciationMethod', 'depreciation_method_id');
    }

    public function category(){
        return $this->belongsTo('App\LeaseAssetCategories', 'category_id');
    }
}
