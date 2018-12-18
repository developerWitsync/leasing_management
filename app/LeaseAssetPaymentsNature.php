<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetPaymentsNature extends Model
{
    protected $table = 'lease_asset_payments_nature';
    protected $fillable = ['title', 'status', 'created_at', 'updated_at'];
}
