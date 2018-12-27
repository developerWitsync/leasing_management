<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseLockYear extends Model
{
    protected $table = 'lease_lock_year';

    protected $fillable = [
    	'id',
        'business_account_id',
        'audit_year1_ended_on',
        'audit_year2_ended_on',
        'created_at',
        'updated_at'
    ];
}
