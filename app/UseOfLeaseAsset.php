<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UseOfLeaseAsset extends Model
{
    protected $table = 'lease_asset_use_master';
    protected $fillable = ['title', 'status', 'created_at', 'updated_at'];
}
