<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAccountingTreatment extends Model
{
    protected $table = 'lease_accounting_treatment';

    protected $fillable = [
        'title',
        'upto_year',
        'status',
        'created_at',
        'updated_at'
    ];
}
