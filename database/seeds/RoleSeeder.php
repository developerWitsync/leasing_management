<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
   /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $roles = [
        [
            'name' => 'Manager',
            'display_name' => 'Create Manager',
            'description'   => 'create Manager blog'
        ],
        [
            'name' => 'Accountant',
            'display_name' => 'Create Accountant',
            'description'   => 'Create Accountant Blog'
        ],
        
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->roles as $value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('roles')->insert($value);
        }
    }
}
