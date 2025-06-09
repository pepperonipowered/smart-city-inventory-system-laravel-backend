<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeSuppliesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officeSupplies = [
            ['supply_name' => 'Sample Supply', 'supply_description' => 'Sample Description', 'category_id' => 1, 'supply_quantity' => 20, 'isc' => 'Sample ISC'],
        ];

        DB::table('office_supplies')->insert($officeSupplies);
    }
}
