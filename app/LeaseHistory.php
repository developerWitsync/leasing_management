<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseHistory extends Model
{
    protected $table = 'lease_history';

    protected $fillable = [
        'lease_id',
        'modify_id',
        'json_data_steps',
        'esclation_payments',
        'payment_anxure',
        'created_at',
        'updated_at'
    ];

    protected function leaseModification(){
        return $this->hasOne('App\ModifyLeaseApplication', 'id','modify_id');
    }
}
