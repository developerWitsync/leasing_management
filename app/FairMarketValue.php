<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FairMarketValue extends Model
{
    protected $table = 'fair_market_value';

    protected $fillable = [
    	'lease_id',
    	'asset_id',
    	'is_market_value_present',
    	'unit',
    	'total_units',
    	'source',
    	'created_at',
    	'updated_at'
    ];
}
