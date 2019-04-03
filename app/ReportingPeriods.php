<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportingPeriods extends Model
{
    protected $table = 'reporting_periods';

    protected $fillable = [
        'title',
        'created_at',
        'updated_at'
    ];
}
