<?php

use Illuminate\Database\Seeder;

class AdminUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'authorised_person_name' => str_random(10),
            'email' => 'admin@yopmail.com',
            'password' => bcrypt('123456'),
            'type' => '1',
            'parent_id' => '0'
        ]);
    }
}
