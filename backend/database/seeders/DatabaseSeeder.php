<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Core seeders
        $this->call([
            // Users and settings
            AdminSeeder::class,
            StaffSeeder::class,
            SettingSeeder::class,

            // Library entities (in order of dependencies)
            CategorySeeder::class,
            PublisherSeeder::class,
            AuthorSeeder::class,
            ResourceSeeder::class,
            CopySeeder::class,

            // Members and circulation
            MemberSeeder::class,
            LoanSeeder::class,
        ]);
    }
}
