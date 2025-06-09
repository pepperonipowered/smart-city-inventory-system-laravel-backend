<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentCopiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $equipmentCopies = [
            ['item_id' => 1, 'is_available' => true, 'copy_num' => 1, 'serial_number' => 'SAMPLE123'],
        ];

        DB::table('equipment_copies')->insert($equipmentCopies);
    }
}
