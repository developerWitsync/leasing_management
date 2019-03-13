<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InitialValuationModels extends Model
{
    protected $table = 'initial_valuation_model';

    protected $fillable = [
        'title',
        'is_default',
        'created_at',
        'updated_at'
    ];
}
