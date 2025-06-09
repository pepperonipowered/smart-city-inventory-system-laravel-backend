<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('source')->insert([
            ['id' => 1, 'sources' => '911', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'sources' => 'CDRRMO', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'sources' => 'Icom Radio', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'sources' => 'EMS Hotline', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
