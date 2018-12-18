<?php

use Illuminate\Database\Seeder;

class ContractEscalationBasis extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = "Percentage Rate Based, Amount Based";
    public function run()
    {
        foreach (explode(',',$this->values) as $title) {
            DB::table('contract_escalation_basis')->insert([
                'title' => $string = trim(preg_replace('/\s\s+/', ' ', $title)),
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
