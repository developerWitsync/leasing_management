<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndustryTypes extends Model
{
    protected $table = 'industry_type';

    protected $fillable = [
        'title','status', 'updated_at', 'created_at'
    ];

}
