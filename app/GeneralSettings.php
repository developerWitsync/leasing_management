<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    protected $table = 'general_settings';

    protected $fillable = ['business_account_id', 'annual_year_end_on', 'date_of_initial_application', 'date_of_initial_application_earlier_date','max_previous_lease_start_year', 'created_at', 'updated_at'];
}
