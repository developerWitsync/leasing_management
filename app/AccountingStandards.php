<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountingStandards extends Model
{
    protected $table = 'accounting_standards';

    protected $fillable = [
        'title',
        'abbreviation',
        'base_date',
        'created_at',
        'updated_at'
    ];
}
