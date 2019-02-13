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
        'updated_at'
    ];
}
