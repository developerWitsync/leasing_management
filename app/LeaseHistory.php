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
        'pv_calculus',
        'historical_annexure',
        'created_at',
        'updated_at'
    ];

    public function lease(){
        return $this->belongsTo('App\Lease', 'lease_id', 'id');
    }

    public function leaseModification(){
        return $this->hasOne('App\ModifyLeaseApplication', 'id','modify_id');
    }
}
