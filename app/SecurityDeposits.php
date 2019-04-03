<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecurityDeposits extends Model
{
    protected $table = 'security_deposits';

    protected $fillable = [
        'lease_id',
        'asset_id',
        'any_security_applicable',
        'refundable_or_adjustable',
        'payment_date_of_security_deposit',
        'type_of_security_deposit',
        'doc',
        'currency',
        'total_amount'
    ];
}
