<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModifyLeaseApplication extends Model
{
    protected $table = 'modify_lease_application';

    protected $fillable = [
        'lease_id','valuation','effective_from','reason','updated_at', 'created_at'
    ];

}
