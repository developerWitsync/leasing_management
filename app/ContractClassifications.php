<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractClassifications extends Model
{
    protected $table = 'contract_classifications';

    protected $fillable = [
        'title',
        'status',
        'created_at',
        'updated_at'
    ];
}
