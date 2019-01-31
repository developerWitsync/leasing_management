<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriesLeaseAssetExcluded extends Model
{
    protected $table = 'categories_lease_asset_excluded';
    protected $fillable = ['category_id', 'business_account_id', 'status', 'created_at', 'updated_at'];

    public function leaseassetcategories(){
        return $this->belongsTo('App\LeaseAssetCategories','category_id','id');
    }
}
