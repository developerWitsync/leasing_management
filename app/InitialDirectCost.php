<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InitialDirectCost extends Model
{
     protected $table = 'initial_direct_cost';

     protected $attributes = [
         'id',
         'lease_id',
         'asset_id',
         'initial_direct_cost_involved',
         'currency',
         'total_initial_direct_cost',
         'created_at',
         'updated_at'
     ];

    protected $fillable = [
    	'lease_id',
    	'asset_id',
    	'initial_direct_cost_involved',
        'currency',
        'total_initial_direct_cost',
    	'created_at',
    	'updated_at'
    ];

    public function supplierDetails(){
        return $this->hasMany('App\SupplierDetails', 'initial_direct_cost_id', 'id')->where('type', '=', 'initial_direct_cost');
    }
}
