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
        ],
        [
            'name' => 'drafts',
            'display_name' => 'Drafts Saved',
            'description'   => 'User can access the lease before review & submit form.'
        ],
        [
            'name' => 'modify_lease',
            'display_name' => 'Modify Lease',
            'description'   => 'User can access the lease after review and submit form.'
        ],
        [
            'name' => 'dashboard',
            'display_name' => 'Dashboard',
            'description' => 'User can access the dashboard for the company.'
        ],
        [
            'name' => 'manage_subscription',
            'display_name' => 'Manage Subscriptions',
            'description' => 'User can manage the subscriptions for the company.'
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
