<?php

use think\migration\Seeder;

class SalaryTax extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'company_id' => 1,
                'type' => 1,
                'tax_begin' => 5000,
                'range_key' => 0,
                'scale' => 3,
                'fast_number' => 0
            ],
            [
                'company_id' => 1,
                'type' => 1,
                'tax_begin' => 5000,
                'range_key' => 36000.00,
                'scale' => 10,
                'fast_number' => 2520.00
            ],
            [
                'company_id' => 1,
                'type' => 1,
                'tax_begin' => 5000,
                'range_key' => 144000.00,
                'scale' => 20,
                'fast_number' => 16920.00
            ],
            [
                'company_id' => 1,
                'type' => 1,
                'tax_begin' => 5000,
                'range_key' => 300000.00,
                'scale' => 25,
                'fast_number' => 31920.00
            ],
            [
                'company_id' => 1,
                'type' => 1,
                'tax_begin' => 5000,
                'range_key' => 420000.00,
                'scale' => 30,
                'fast_number' => 52920.00
            ],
            [
                'company_id' => 1,
                'type' => 1,
                'tax_begin' => 5000,
                'range_key' => 660000.00,
                'scale' => 35,
                'fast_number' => 85920.00
            ],
            [
                'company_id' => 1,
                'type' => 1,
                'tax_begin' => 5000,
                'range_key' => 960000.00,
                'scale' => 45,
                'fast_number' => 181920.00
            ],
        ];
        $this->table('salary_tax')->insert($data)->save();
    }
}