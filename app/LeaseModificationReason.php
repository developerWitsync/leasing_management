<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseModificationReason extends Model
{
    protected $table = 'lease_modification_reasons';
    protected $fillable = ['title', 'status', 'created_at', 'updated_at'];
}
