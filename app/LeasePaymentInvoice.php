<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeasePaymentInvoice extends Model
{
    protected $table = 'lease_payment_invoice';

    protected $fillable = [
    	'lease_id',
    	'asset_id',
    	'lease_payment_invoice_received'
    ];
}
