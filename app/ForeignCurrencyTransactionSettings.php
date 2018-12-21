<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForeignCurrencyTransactionSettings extends Model
{
    protected $table = 'foreign_currency_transaction_settings';

    protected $fillable = [
        'business_account_id',
        'foreign_exchange_currency',
        'valid_from',
        'valid_to',
        'exchange_rate',
        'base_currency',
        'created_at',
        'updated_at'
    ];
}
