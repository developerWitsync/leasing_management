<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetCountries extends Model
{
    protected $table = 'lease_asset_countries';

    protected $fillable = [
        'business_account_id',
        'country_id',
        'created_at',
        'updated_at'
    ];

    public function country(){
        return $this->hasOne('App\Countries', 'id', 'country_id');
    }
}
