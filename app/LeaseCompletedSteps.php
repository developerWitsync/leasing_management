<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseCompletedSteps extends Model
{
     protected $table = 'lease_completed_steps';

    protected $fillable = [
    	'lease_id',
    	'completed_step'
    ];

   
}
