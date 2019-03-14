<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialReportingPeriodSetting extends Model
{
    protected  $table = 'financial_reporting_period';

    protected $fillable = [
        'reporting_period_id',
        'business_account_id',
        'created_at',
        'updated_at'
    ];
}
