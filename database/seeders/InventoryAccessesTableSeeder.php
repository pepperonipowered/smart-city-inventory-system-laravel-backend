<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoryAccessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $inventoryAccesses = [
            [
                'for_dashboard' => true,
                'for_transactions' => true,
                'for_inventory' => true,
                'for_offices' => true,
                'for_categories' => true,
                'for_borrowers' => true,
                'for_users' => true,
                'user_id' => 1,
            ],
            [
                'for_dashboard' => true,
                'for_transactions' => true,
                'for_inventory' => true,
                'for_offices' => true,
                'for_categories' => true,
                'for_borrowers' => true,
                'for_users' => true,
                'user_id' => 2,
            ],
            [
                'for_dashboard' => true,
                'for_transactions' => true,
                'for_inventory' => true,
                'for_offices' => true,
                'for_categories' => true,
                'for_borrowers' => true,
                'for_users' => true,
                'user_id' => 3,
            ],
        ];

        DB::table('inventory_accesses')->insert($inventoryAccesses);
    }
}
