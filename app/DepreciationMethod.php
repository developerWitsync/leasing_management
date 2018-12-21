<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DepreciationMethod extends Model
{
    protected $table = 'depreciation_method';

    protected $fillable = [
        'title',
        'created_at',
        'updated_at'
    ];
}
