<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseResidualValue extends Model
{
    protected $table = ' lease_residual_value_gurantee';

    protected $fillable = [
        'asset_id',
        'any_residual_value_gurantee',
        'lease_payemnt_nature_id',
        'amount_determinable',
        'foreign_currency_id',
        'no_of_unit_lease_asset',
        'residual_gurantee_value',
        'total_residual_gurantee_value',
        'other_desc',
        'residual_file',
        'created_at',
        'updated_at'
    ];
}
