<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeOfAssistanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('type_of_assistance')->insert([
            ['id' => 1, 'assistance' => 'Medical Assistance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'assistance' => 'Police Assistance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'assistance' => 'Fire Assistance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'assistance' => 'Rescue Assistance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'assistance' => 'General Assistance', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'assistance' => 'Others', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
