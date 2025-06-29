<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        // Employee Permissions
        Permission::firstOrCreate(['name' => 'create requisition']);
        Permission::firstOrCreate(['name' => 'view own requisitions']);
        Permission::firstOrCreate(['name' => 'view requisition status']);

        // Admin Permissions
        Permission::firstOrCreate(['name' => 'manage items']); // Covers Add/Remove/Update
        Permission::firstOrCreate(['name' => 'manage suppliers']); // Covers Add/Remove/Update
        Permission::firstOrCreate(['name' => 'view stock list']);
        Permission::firstOrCreate(['name' => 'approve requisitions']);
        Permission::firstOrCreate(['name' => 'reject requisitions']);
        // Store Executive Permissions
        Permission::firstOrCreate(['name' => 'receive items']);
        Permission::firstOrCreate(['name' => 'view received items']);
        Permission::firstOrCreate(['name' => 'view stock details']);
        Permission::firstOrCreate(['name' => 'issue items']);

        // Create roles and assign permissions
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $employeeRole->givePermissionTo([
            'create requisition',
            'view own requisitions',
            'view requisition status',
        ]);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'manage items',
            'manage suppliers',
            'view stock list',
            'approve requisitions',
            'reject requisitions',
        ]);

        $storeExecutiveRole = Role::firstOrCreate(['name' => 'store_executive']);
        $storeExecutiveRole->givePermissionTo([
            'receive items',
            'view received items',
            'view stock details',
            'issue items',
        ]);

        // Assign roles to users (example)
        // Ensure you have some users in your database or create them here.
        // For example, if User ID 1 is an employee, ID 2 is an admin, ID 3 is a store executive.
        // You might create a default admin user here.

        // Example: Create an admin user and assign role

        $adminUser = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'), // Change this in production!
        ]);
        $adminUser->assignRole('admin');

        $employeeUser = User::firstOrCreate([
            'email' => 'employee@example.com'
        ], [
            'name' => 'Employee User',
            'password' => bcrypt('password'),
        ]);
        $employeeUser->assignRole('employee');

        $storeExecutiveUser = User::firstOrCreate([
            'email' => 'executive@example.com'
        ], [
            'name' => 'Store Executive User',
            'password' => bcrypt('password'),
        ]);
        $storeExecutiveUser->assignRole('store_executive');
    }
}
