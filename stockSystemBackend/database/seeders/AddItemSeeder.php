<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Example of creating a specific item
        \App\Models\Item::create([
            'name' => 'Samsung Galaxy S21',
            'description' => 'This is a sample item for seeding.',
            'status' => 'active',
        ]);

        \App\Models\Item::create([
            'name' => 'Apple iPhone 12',
            'description' => 'This is a sample item for seeding.',
            'status' => 'active',
        ]);

        \App\Models\Item::create([
            'name' => 'Dell XPS 13',
            'description' => 'This is a sample item for seeding.',
            'status' => 'active',
        ]);
    }
}
