<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    protected $table = 'countries';
    protected $fillable = [
        'name', 'iso_code', 'latitude', 'longitude', 'status', 'trash', 'created_at', 'updated_at'
    ];

    public  function states(){
        return $this->hasMany('App\States', 'country_id', 'id');
    }
}
