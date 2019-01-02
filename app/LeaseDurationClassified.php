<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseDurationClassified extends Model
{
    protected $table = 'lease_duarion_classified';

    protected $fillable = [
        'asset_id',
        'lease_id',
        'lease_start_date',
        'lease_end_date',
        'lease_classified',
        'created_at',
        'updated_at'
    ];
}
