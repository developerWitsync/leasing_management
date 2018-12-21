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
            'name' => 'Create',
            'display_name' => 'Create Posts',
            'description'   => 'Create new blog posts'
        ],
        [
            'name' => 'Edit',
            'display_name' => 'Edit Users',
            'description'   => 'Edit existing users'
        ],
         [
            'name' => 'Select',
            'display_name' => 'Select Posts',
            'description'   => 'Select blog posts'
        ],
        [
            'name' => 'Delete',
            'display_name' => 'Delete Users',
            'description'   => 'Delete existing users'
        ],
        
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
