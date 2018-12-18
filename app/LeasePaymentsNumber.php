<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeasePaymentsNumber extends Model
{
    protected $table = 'no_of_lease_payments';
    protected $fillable = ['business_account_id', 'number', 'created_at', 'updated_at'];
}
