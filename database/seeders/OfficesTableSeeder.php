<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offices = [
            ['office_name' => 'City Mayors Office'],
            ['office_name' => 'Sangguniang Panlungsod ng Baguio'],
            ['office_name' => 'City Administrators Office'],
            ['office_name' => 'City Human Resource Management Office'],
            ['office_name' => 'City General Services Office'],
            ['office_name' => 'City Building And Architecture Office'],
            ['office_name' => 'City Planning, Development, and Sustainability Office'],
            ['office_name' => 'City Accounting Office'],
            ['office_name' => 'City Assessors Office'],
            ['office_name' => 'City Budget Office'],
            ['office_name' => 'City Treasurers Office'],
            ['office_name' => 'City Civil Registry Office'],
            ['office_name' => 'City Legal Office'],
            ['office_name' => 'City Disaster Risk Reduction and Management Office'],
            ['office_name' => 'City Veterinary and Agriculture Office'],
            ['office_name' => 'City Social Welfare and Development Office'],
            ['office_name' => 'City Health Services Office'],
            ['office_name' => 'City Environment And Parks Management Office'],
            ['office_name' => 'City Engineering Office'],
            ['office_name' => 'Baguio City Police Office'],
            ['office_name' => 'Bureau Of Fire Protection'],
            ['office_name' => 'Bureau Of Jail Management And Penology (Female)'],
            ['office_name' => 'Bureau Of Jail Management And Penology (Male)'],
            ['office_name' => 'Commission On Audit'],
            ['office_name' => 'Department Of Education'],
            ['office_name' => 'Municipal Trial Court'],
            ['office_name' => 'Parole'],
            ['office_name' => 'Prosecutors Office'],
            ['office_name' => 'Public Attorneys Office'],
            ['office_name' => 'Regional Trial Court'],
            ['office_name' => 'DILG - Baguio City Field Office'],
        ];

        DB::table('offices')->insert($offices);
    }
}
