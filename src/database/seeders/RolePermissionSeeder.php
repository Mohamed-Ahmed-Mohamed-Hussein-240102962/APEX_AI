<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Librarian']);
        Role::create(['name' => 'Member']);

        $admin = User::firstOrCreate([
            'email' => 'admin@library.com'
        ], [
            'name' => 'System Admin',
            'password' => Hash::make('password')
        ]);
        
        $admin->assignRole('Admin');
    }
}
