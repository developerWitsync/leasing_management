<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
    protected $table = 'cms';
    protected $fillable = [
        'title', 'slug', 'description', 'meta_title', 'meta_description', 'meta_keyword', 'status', 'created_at', 'updated_at'
    ];
}
