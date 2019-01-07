<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseIncentives extends Model
{
    protected $table = 'lease_incentives';

    protected $fillable = [
        'asset_id',
        'lease_id',
        'is_any_lease_incentives_receivable',
        'currency',
        'total_lease_incentives',
        'created_at',
        'updated_at'
    ];
}
