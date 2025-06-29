<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddSupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Supplier::create([
            'name' => 'Tech Supplies Ltd.',
            'address' => '123 Tech Street, Silicon Valley',
            'phone' => '0123-4567890',
            'email' => 'abc@gmail.com',
            'status' => 'active',
        ]);

        \App\Models\Supplier::create([
            'name' => 'Gadget World Inc.',
            'address' => '456 Gadget Avenue, Tech City',
            'phone' => '0987-6543210',
            'email' => 'bbb@gmail.com'
        ]);
    }
}
