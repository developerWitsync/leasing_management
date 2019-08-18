<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExchangeRates extends Model
{
    protected $table = 'exchange_rates';

    protected $fillable = ['foreign_currency_id', 'date', 'rate', 'created_at', 'updated_at'];
}
