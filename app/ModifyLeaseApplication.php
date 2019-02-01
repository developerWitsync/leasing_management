<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModifyLeaseApplication extends Model
{
    protected $table = 'modify_lease_application';

    protected $fillable = [
        'business_account_id','lease_id','valuation','effective_from','reason','updated_at', 'created_at'
    ];

    public function lease(){
        return $this->hasOne('App\Lease', 'id', 'lease_id');
    }
}
