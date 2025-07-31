<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            \App\Domains\User\Database\Seeders\AdminUserSeeder::class,
            \App\Domains\Group\Database\Seeders\GroupSeeder::class,
            \App\Domains\Customer\Database\Seeders\CustomerSeeder::class,
            \App\Domains\Project\Database\Seeders\ProjectSeeder::class,
            \App\Domains\Update\Database\Seeders\UpdateSeeder::class,
        ]);
    }
}
