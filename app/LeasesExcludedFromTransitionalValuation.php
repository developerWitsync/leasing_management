<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeasesExcludedFromTransitionalValuation extends Model
{
    protected $table = 'leases_excluded_from_transitional_valuation';
    protected $fillable = [
        'title',
        'value_for',
        'status',
        'created_at',
        'updated_at'
    ];
}
