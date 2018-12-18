<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseContractDuration extends Model
{

    protected $table = 'lease_contract_duration';

    protected $fillable = [
        'lower_limit',
        'upper_limit',
        'month_range_description',
        'title',
        'status',
        'created_at',
        'updated_at'
    ];
}
