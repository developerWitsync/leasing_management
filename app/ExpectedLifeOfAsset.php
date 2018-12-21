<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpectedLifeOfAsset extends Model
{
    protected $table = 'expected_useful_life_of_asset';

    protected $fillable = [
        'business_account_id',
        'years',
        'created_at',
        'updated_at'
    ];
}
