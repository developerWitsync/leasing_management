<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOption extends Model
{
    protected $table = 'purchase_option';

    protected $fillable = [
    	'lease_id',
    	'asset_id',
    	'purchase_option_clause',
        'purchase_option_exerecisable',
        'expected_purchase_date',
        'expected_lease_end_date',
    	'currency',
    	'purchase_price',
    	'created_at',
    	'updated_at'
    ];
}
