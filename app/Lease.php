<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    protected $table = 'lease';

    protected $fillable = [
        'id',
        'business_account_id',
        'lessor_name',
        'lease_type_id',
        'lease_contract_id',
        'lease_code',
        'file',
        'status',
        'total_assets',
        'created_at',
        'updated_at'
    ];

    public function leaseType(){
        return $this->belongsTo('App\ContractClassifications', 'lease_type_id');
    }

    public function assets(){
        return $this->hasMany('App\LeaseAssets','lease_id', 'id');
    }

}
