<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    protected $table = 'general_settings';

    protected $fillable = [
        'business_account_id',
        'date_of_initial_application',
        'date_of_initial_application_earlier_date',
        'min_previous_first_lease_start_year',
        'max_lease_end_year',
        'created_at',
        'updated_at',
        'is_initial_date_of_application_saved',
        'effective_date_of_standard',
        'annual_financial_reporting_year_end_date',
        'final_base_date',
        'ledger_level'
    ];
}
