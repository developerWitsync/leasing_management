<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DismantlingCosts extends Model
{
    protected $table = 'dismantling_costs';

    protected $fillable = [
        'lease_id',
        'asset_id',
        'cost_of_dismantling_incurred',
        'obligation_cost_of_dismantling_incurred',
        'currency',
        'details',
        'total_estimated_cost'
    ];


    public function supplierDetails(){
        return $this->hasMany('App\SupplierDetails', 'initial_direct_cost_id', 'id')->where('type', '=', 'dismantling_cost');
    }
}
