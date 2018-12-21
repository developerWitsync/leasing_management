<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportingCurrencySettings extends Model
{
    protected $table = 'reporting_currency_settings';

    protected $fillable = [
        'business_account_id',
        'statutory_financial_reporting_currency',
        'internal_company_financial_reporting_currency',
        'currency_for_lease_reports',
        'lease_report_same_as_statutory_reporting',
        'internal_same_as_statutory_reporting',
        'is_foreign_transaction_involved',
        'created_at',
        'updated_at'
    ];
}
