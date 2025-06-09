<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficeEquipmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officeEquipments = [
            ['equipment_name' => 'Sample Equipment', 'equipment_description' => 'Sample Description', 'category_id' => 1, 'isc' => 'Sample ISC'],
        ];

        DB::table('office_equipments')->insert($officeEquipments);
    }
}
