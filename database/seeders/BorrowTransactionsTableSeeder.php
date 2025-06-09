<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BorrowTransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $borrowTransactions = [
            ['borrower_id' => 1, 'borrow_date' => Carbon::now()->subDays(10), 'return_date' => null, 'lender_id' => 1, 'remarks' => 'Sample Description', 'isc' => 'Sample ISC'],
            ['borrower_id' => 1, 'borrow_date' => Carbon::now()->subDays(20), 'return_date' => null, 'lender_id' => 1, 'remarks' => 'Sample Description', 'isc' => 'Sample ISC'],
        ];

        DB::table('borrow_transactions')->insert($borrowTransactions);
    }
}
