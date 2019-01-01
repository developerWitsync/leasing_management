<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseTerminationOption extends Model
{
     protected $table = 'fair_market_value';

    protected $fillable = [
    	'lease_id',
    	'asset_id',
    	'lease_termination_option_available',
        'exercise_termination_option_available',
        'termination_penalty_applicable',
        'lease_end_date',
    	'currency',
    	'termination_penalty',
    	'created_at',
    	'updated_at'
    ];
}
