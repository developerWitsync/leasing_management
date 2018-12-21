<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeasePaymentsFrequency extends Model
{
    protected $table = 'lease_payments_frequency';
    protected $fillable = ['title', 'status', 'created_at', 'updated_at'];
}
