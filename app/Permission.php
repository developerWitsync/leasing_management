<?php

 namespace App;
 
use Illuminate\Database\Seeder;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
	protected $table = 'permissions';
    protected $fillable = ['name', 'display_name', 'description', 'created_at', 'updated_at'];
}