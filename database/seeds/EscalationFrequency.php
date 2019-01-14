<?php

use Illuminate\Database\Seeder;

class EscalationFrequency extends Seeder
{
    protected $values = [
        [
            'title' => 'One Time in Year',
            'frequency'    => '1'
        ],
        [
            'title' => 'Two Times in a Year',
            'frequency'    => '2'
        ],
        [
            'title' => 'Three Times in a Year',
            'frequency'    => '3'
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
            DB::table('escalation_frequency')->insert($value);
        }
    }
}
