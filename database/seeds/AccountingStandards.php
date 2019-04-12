<?php

use Illuminate\Database\Seeder;

class AccountingStandards extends Seeder
{

    protected $values = [
        [
            'title' => 'IFRS- 16 (International Financial Reporting Standard)',
            'abbreviation' => 'IFRS- 16',
            'base_date' => '2019-01-01'
        ],
        [
            'title' => 'IND-AS-116 (Indian Accounting Standard)',
            'abbreviation' => 'IND-AS-116',
            'base_date' => '2019-04-01'
        ],
        [
            'title' => 'SFRS(I)16 - (Singapore Financial Reporting Standard (International))',
            'abbreviation' => 'SFRS(I)16',
            'base_date' => '2019-01-01'
        ],
        [
            'title' => 'MFRS 16 - (Malaysian Financial Reporting Standard)',
            'abbreviation' => 'MFRS 16',
            'base_date' => '2019-01-01'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->values as $value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['updated_at'] = date('Y-m-d H:i:s');
            DB::table('accounting_standards')->insert($value);
        }
    }
}
