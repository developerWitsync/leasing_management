<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateTypes extends Model
{
    protected $table = 'rate_types';

    protected $fillable = [
        'id', 'title', 'status', 'created_at', 'updated_at'
    ];
}
