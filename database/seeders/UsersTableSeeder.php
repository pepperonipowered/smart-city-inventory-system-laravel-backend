<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'firstName' => 'Adam Bert',
                'middleName' => 'Michael',
                'lastName' => 'Lacay',
                'email' => 'adambert@gmail.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('p@ssword123'),
                'for_911' => 1,
                'for_inventory' => 1,
                'for_traffic' => 1,
                'role' => 1,
            ],
            [
                'firstName' => 'Inventory Account',
                'middleName' => 'inv',
                'lastName' => 'inv',
                'email' => 'inventory@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('p@ssword123'),
                'for_911' => 0,
                'for_inventory' => 1,
                'for_traffic' => 0,
                'role' => 0,
            ],
            [
                'firstName' => 'Traffic Account',
                'middleName' => 'tra',
                'lastName' => 'tra',
                'email' => 'traffic@example.com',
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make('p@ssword123'),
                'for_911' => 0,
                'for_inventory' => 0,
                'for_traffic' => 1,
                'role' => 0,
            ],
        ];

        DB::table('users')->insert($users);
    }
}
