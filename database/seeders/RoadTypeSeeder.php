<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoadType;

class RoadTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Intersection',
            'Rotunda',
            'Street',
            'Entry Point',
            'Road'
        ];

        foreach ($types as $type) {
            RoadType::create(['type_name' => $type]);
        }
    }
}
