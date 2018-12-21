<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeasePaymentComponents extends Model
{
    protected $table = 'lease_payments_components';
    protected $fillable = ['title', 'status', 'created_at', 'updated_at'];
}
