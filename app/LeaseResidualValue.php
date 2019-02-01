<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseResidualValue extends Model
{
    protected $table = 'lease_residual_value_gurantee';

    protected $fillable = [
        'asset_id',
        'lease_id',
        'any_residual_value_gurantee',
        'lease_payemnt_nature_id',
        'variable_basis_id',
        'amount_determinable',
        'currency',
        'similar_asset_items',
        'residual_gurantee_value',
        'total_residual_gurantee_value',
        'other_desc',
        'attachment',
        'created_at',
        'updated_at'
    ];
}
