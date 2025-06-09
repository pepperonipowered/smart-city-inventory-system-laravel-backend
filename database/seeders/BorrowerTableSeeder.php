<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BorrowerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $borrowers = [
            ['borrowers_name' => 'Sample Borrower', 'borrowers_contact' => '09123456789', 'office_id' => 1, 'deleted_by' => null],
        ];

        DB::table('borrowers')->insert($borrowers);
    }
}
