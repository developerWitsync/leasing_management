<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierDetails extends Model
{
    protected $table = 'supplier_details';

    protected $fillable = [
    	'initial_direct_cost_id',
    	'supplier_name',
    	'direct_cost_description',
    	'expense_date',
    	'supplier_currency',
    	'amount',
    	'rate',
    	'created_at',
    	'updated_at'
    ];
}
