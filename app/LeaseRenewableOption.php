<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseRenewableOption extends Model
{
    protected $table = 'lease_renewal_option';

    protected $fillable = [
        'asset_id',
        'lease_id',
        'is_renewal_option_under_contract',
        'is_reasonable_certainity_option',
        'expected_lease_end_Date',
        'created_at',
        'updated_at'
    ];
}
