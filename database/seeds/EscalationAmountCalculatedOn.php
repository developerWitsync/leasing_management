<?php

use Illuminate\Database\Seeder;

class EscalationAmountCalculatedOn extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $values = "Fixed Lease Payments,Variable Lease Payments with Determinable Amounts";
    public function run()
    {
        foreach (explode(',',$this->values) as $title) {
            DB::table('escalation_amount_calculated_on')->insert([
                'title' => $string = trim(preg_replace('/\s\s+/', ' ', $title)),
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
