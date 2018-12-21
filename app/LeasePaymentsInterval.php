<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeasePaymentsInterval extends Model
{
    protected $table = 'lease_payment_interval';
    protected $fillable = [
        'title',
        'status',
        'created_at',
        'updated_at'
    ];
}
