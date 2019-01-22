<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetsNumberSettings extends Model
{
    protected $table = 'un_lease_assets_numbers_settings';
    protected $fillable = ['business_account_id', 'number', 'status', 'created_at', 'updated_at'];

    public function isUsed(){
        return $this->hasMany('App\Lease', 'total_assets', 'number')->whereIn('business_account_id', getDependentUserIds());
    }
}
