<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomPlansRequest extends Model
{
    protected $table = 'custom_plans_request';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'no_of_lease_assets',
        'no_of_users',
        'hosting_type',
        'comments',
        'created_at',
        'updated_at'
    ];
}
