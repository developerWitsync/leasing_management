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
        'similar_asset_items',
        'created_at',
        'updated_at'
    ];
}
