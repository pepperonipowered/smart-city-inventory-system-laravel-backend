<?php

namespace Database\Seeders;

use App\Models\InventoryAccess;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'testsad@example.com',
        // ]);

        $this->call([
            // seeder for SCCC 911 System
            TypeOfAssistanceSeeder::class,
            ActionsTakenSeeder::class,
            SourceSeeder::class,
            IncidentSeeder::class,
            BarangaySeeder::class,
            UrgencySeeder::class,

            // seeder for SCCC Inventory System
            UsersTableSeeder::class,
            OfficesTableSeeder::class,
            BorrowerTableSeeder::class,
            BorrowTransactionsTableSeeder::class,
            BorrowTransactionItemsTableSeeder::class,
            CategoriesTableSeeder::class,
            OfficeEquipmentsTableSeeder::class,
            OfficeSuppliesTableSeeder::class,
            EquipmentCopiesTableSeeder::class,
            InventoryAccessesTableSeeder:: class,

            //seeder for SCCC Traffic System
            StatusTrafficSeeder::class,
            RoadTypeSeeder::class,
            RoadSeeder::class,
            InboundSeeder::class,
            OutboundSeeder::class,
        ]);
    }
}
