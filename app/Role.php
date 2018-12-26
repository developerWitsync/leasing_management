<?php 

namespace App;

use Zizaco\Entrust\EntrustRole;
use Illuminate\Support\Facades\Config;

class Role extends EntrustRole
{
	protected $table = 'roles';
    protected $fillable = [
        'name',
        'business_account_id',
        'display_name',
        'description',
        'created_at',
        'updated_at'
    ];

    public function users()
	{
	return $this->belongsToMany(Config::get('auth.providers.users.model'),Config::get('entrust.role_user_table'),Config::get('entrust.role_foreign_key'),Config::get('entrust.user_foreign_key'));

	}
}