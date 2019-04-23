<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PvCalculus extends Model
{
    protected $table = 'pv_calculus';

    protected $fillable = ['asset_id', 'calculus', 'created_at', 'updated_at'];
}
