<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeasePaymentsBasis extends Model
{
    protected $table = 'lease_payments_basis';
    protected $fillable = ['business_account_id', 'title', 'status', 'created_at', 'updated_at'];
}
