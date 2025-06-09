<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\StatusTraffic;

class StatusTrafficSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status_names = [
            'Light',
            'Moderate',
            'Heavy',
        ];

        foreach ($status_names as $status_name) {
            StatusTraffic::create(['status_name' => $status_name]);
        }
    }
}
