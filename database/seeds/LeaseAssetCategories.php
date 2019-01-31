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
        [
                'title' => 'Tangible Properties - Land',
                'is_capitalized' => '1'
        ],
        [
                'title' => 'Tangible Properties - Other than Land',
                'is_capitalized' => '1'
        ],
        [
                'title' => 'Plants & Equipments',
                'is_capitalized' => '1'
        ],
        [
                'title'  => 'Agricultural Assets',
                'is_capitalized' => '1'
        ],
        [
                'title' => 'Biological Assets',
                'is_capitalized' => '0'
        ],
        [
                'title' => 'Investment Properties',
                'is_capitalized' => '1'
        ],
        [
                'title' => 'Intangible Assets',
                'is_capitalized' => '1'
        ],
        [
                'title' => 'Intangible Assets under Licensing Arrangement',
                'is_capitalized' => '0'
        ]
    ];

    public function run()
    {
        foreach ($this->values as $value) {
            DB::table('lease_assets_categories')->insert([
                'title' => trim(preg_replace('/\s\s+/', ' ', $value['title'])),
                'is_capitalized' => $value['is_capitalized'],
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
