<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ledgers extends Model
{
  protected $table = 'ledgers';

  protected $fillable = [
    'business_account_id',
    'ledger_level',
    'category_id',
    'type',
    'account_name',
    'account_code',
    'created_at',
    'updated_at'
  ];
}
