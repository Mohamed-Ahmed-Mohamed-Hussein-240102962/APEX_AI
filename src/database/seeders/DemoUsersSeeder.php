<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'admin',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        $lib = User::firstOrCreate([
            'email' => 'librarian@example.com',
        ], [
            'name' => 'lib',
            'password' => Hash::make('password'),
        ]);
        $lib->assignRole('Librarian');

        $mohamed = User::firstOrCreate([
            'email' => 'member@example.com',
        ], [
            'name' => 'mohamed',
            'password' => Hash::make('password'),
        ]);
        $mohamed->assignRole('Member');
    }
}
