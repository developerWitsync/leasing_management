<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $table = 'states';

    protected $fillable = [
        'country_id',
        'state_name',
        'state_3_code',
        'state_2_code',
        'created_at',
        'updated_at'
    ];
}
