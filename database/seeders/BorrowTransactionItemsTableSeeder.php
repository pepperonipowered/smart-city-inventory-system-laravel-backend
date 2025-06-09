<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BorrowTransactionItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $borrowTransactionItems = [
            ['transaction_id' => 1, 'item_copy_id' => 1, 'returned' => true, 'item_type' => 'Equipment Copy', 'quantity' => 1],
            ['transaction_id' => 2, 'item_copy_id' => 1, 'returned' => false, 'item_type' => 'Office Supply', 'quantity' => 1],
        ];

        DB::table('borrow_transaction_items')->insert($borrowTransactionItems);
    }
}
