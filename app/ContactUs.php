<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    protected $table = 'contact_us';

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'no_of_realestate', 'comments', 'created_at', 'updated_at'];
}
