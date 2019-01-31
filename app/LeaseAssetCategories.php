<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetCategories extends Model
{
    protected $table = 'lease_assets_categories';

    protected $fillable = [
        'title',
        'is_capitalized',
        'status',
        'created_at',
        'updated_at'
    ];

    public function subcategories(){
        return $this->hasMany('App\LeaseAssetSubCategorySetting', 'category_id', 'id');
    }
    
}
