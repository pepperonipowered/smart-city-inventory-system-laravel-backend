<?php

namespace App\Observers;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Database\Seeders\BarangaySeeder;
use Database\Seeders\IncidentSeeder;
use Database\Seeders\SourceSeeder;
use Database\Seeders\UrgencySeeder;
use Database\Seeders\TypeOfAssistanceSeeder;
use Database\Seeders\ActionsTakenSeeder;

class UserObserver
{
    //
    public function created(User $user)
    {
        // Run the seeders when the first user is created
        if (User::count() === 1) {
            // For 911 Seeder
            Artisan::call('db:seed', ['--class' => TypeOfAssistanceSeeder::class]);
            Artisan::call('db:seed', ['--class' => ActionsTakenSeeder::class]);
            Artisan::call('db:seed', ['--class' => SourceSeeder::class]);
            Artisan::call('db:seed', ['--class' => IncidentSeeder::class]);
            Artisan::call('db:seed', ['--class' => BarangaySeeder::class]);
            Artisan::call('db:seed', ['--class' => UrgencySeeder::class]);
        }
    }
}
