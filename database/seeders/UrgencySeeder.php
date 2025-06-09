<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UrgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // old urgency
        // DB::table('urgency')->insert([
        //     ['id' => 1, 'urgency' => 'Emergent', 'description' => 'Poses an immediate threat to life. Immediate cure/seen by a doctor within 10 minutes.'],
        //     ['id' => 2, 'urgency' => 'Urgent', 'description' => 'Requiring prompt care, but can wait for hours. Condition that must be treated by a doctor, but need more that 2 resources.'],
        //     ['id' => 3, 'urgency' => 'Less Urgent', 'description' => 'Condition that must be treated by a Doctor, but need one (1) resources.'],
        //     ['id' => 4, 'urgency' => 'Non-Urgent', 'description' => 'Condition needs attention, but time is not a critical factor.'],
        // ]);

        // new urgency
        DB::table('urgency')->insert([
            [
                'id' => 1,
                'urgency' => 'Life-Saving',
                'description' => 'Requires immediate life-saving intervention with zero delay (e.g., cardiac arrest, hostage situation, armed robbery in action).',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'urgency' => 'Critical',
                'description' => 'Immediate threat to life or property. Requires immediate response within minutes (e.g., active robbery, severe injury, major fire).',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'urgency' => 'High Priority',
                'description' => 'Serious situation needing prompt attention, but not immediately life-threatening (e.g., theft in progress, moderate injuries, fire under control).',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'urgency' => 'Moderate',
                'description' => 'Situation requires attention but can tolerate short delays (e.g., property damage, minor assault, controlled disputes).',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'urgency' => 'Low Priority',
                'description' => 'Minor or non-urgent situations where delayed response is acceptable (e.g., noise complaints, lost items, reports for documentation).',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
