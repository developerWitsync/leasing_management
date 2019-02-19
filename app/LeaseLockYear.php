<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseLockYear extends Model
{
    protected $table = 'lease_lock_year';

    protected $fillable = [
    	'id',
        'business_account_id',
        'start_date',
        'status',
        'created_at',
        'updated_at'
    ];
}
