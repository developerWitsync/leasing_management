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
        'escalation_clause_applicable',
        'is_completed',
        'created_at',
        'updated_at'
    ];

    public function leaseType(){
        return $this->belongsTo('App\ContractClassifications', 'lease_type_id');
    }

    public function assets(){
        return $this->hasMany('App\LeaseAssets','lease_id', 'id');
    }


    public function leaseInvoice(){
        return $this->hasMany('App\LeasePaymentInvoice','lease_id', 'id');
    }

    public function modifyLeaseApplication(){
        return $this->hasMany('App\ModifyLeaseApplication', 'lease_id', 'id');
    }

    public function isSubsequentModification(){
        $modify_lease_data = $this->modifyLeaseApplication->last();
        return ($modify_lease_data && $modify_lease_data->valuation == "Subsequent Valuation");
    }
}
