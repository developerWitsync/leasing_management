<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EscalationFrequency extends Model
{
    protected $table = 'escalation_frequency';

    protected $fillable = [
        'title',
        'frequency',
        'created_at',
        'updated_at'
    ];
}
