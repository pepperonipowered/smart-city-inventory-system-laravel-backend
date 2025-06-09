<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActionsTakenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('actions_taken')->insert([
            ['id' => 1, 'actions' => 'Pending', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'actions' => 'Referred', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'actions' => 'Solved', 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
