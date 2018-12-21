<?php

use Illuminate\Database\Seeder;

class LeaseAssetCategories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = [
        'Tangible Properties - Land',
        'Tangible Properties - Other than Land',
        'Plants & Equipments',
        'Agricultural Assets',
        'Biological Assets',
        'Investment Properties',
        'Intangible Assets',
        'Intangible Assets under Licensing Arrangement'
    ];

    public function run()
    {
        foreach ($this->values as $title) {
            DB::table('lease_assets_categories')->insert([
                'title' => $string = trim(preg_replace('/\s\s+/', ' ', $title)),
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
