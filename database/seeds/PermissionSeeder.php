<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
	/**
     * Run the database seeds.
     *
     * @return void
     */
    protected $permission = [
        [
            'name' => 'add_lease',
            'display_name' => 'Add New Lease',
            'description'   => 'The user can create a new lease. If the user is not a parent user than the settings will be used from his parent account.'
        ],
        [
            'name' => 'settings',
            'display_name' => 'Settings',
            'description'   => 'User who has been provided the access will be responsible for managing the settings on behalf of the main business account.'
        ]
        
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->permission as $value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('permissions')->insert($value);
        }
    }
   
}
