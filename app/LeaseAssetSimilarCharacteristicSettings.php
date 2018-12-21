<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetSimilarCharacteristicSettings extends Model
{
    protected $table = 'la_similar_charac_number';
    protected $fillable = ['business_account_id', 'number', 'status', 'created_at', 'updated_at'];
}
