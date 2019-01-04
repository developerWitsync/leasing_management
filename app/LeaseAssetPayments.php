<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseAssetPayments extends Model
{
    protected $table = 'lease_assets_payments';

    protected $fillable = [
        'asset_id',
        'name',
        'type',
        'nature',
        'variable_basis',
        'variable_amount_determinable',
        'description',
        'payment_interval',
        'payout_time',
        'first_payment_start_date',
        'last_payment_end_date',
        'payment_currency',
        'using_lease_payment',
        'payment_per_interval_per_unit',
        'total_amount_per_interval',
        'attachment',
        'created_at',
        'updated_at'
    ];

    public function category(){
        return $this->belongsTo('App\LeasePaymentComponents', 'type', 'id');
    }

    public function asset(){
        return $this->belongsTo('App\LeaseAssets', 'asset_id', 'id');
    }

    public function paymentNature(){
        return $this->belongsTo('App\LeaseAssetPaymentsNature', 'nature', 'id');
    }
}
