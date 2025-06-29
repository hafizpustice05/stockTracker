<?php

namespace Database\Seeders;

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
        \App\Models\User::factory()->admin()->create(['email' => 'admin@example.com', 'password' => bcrypt('password')]);
        \App\Models\User::factory()->storeExecutive()->create(['email' => 'executive@example.com', 'password' => bcrypt('password')]);
        \App\Models\User::factory()->employee()->create(['email' => 'employee@example.com', 'password' => bcrypt('password')]);

        $this->call([
            RolesAndPermissionsSeeder::class,
            AddItemSeeder::class,
            AddSupplierSeeder::class,
        ]);
    }
}
