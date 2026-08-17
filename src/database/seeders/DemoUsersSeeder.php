<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoUsersSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'name' => 'admin',
            'password' => Hash::make('password')
        ]);
        $admin->assignRole('Admin');

        $lib = User::firstOrCreate([
            'email' => 'lib@gmail.com'
        ], [
            'name' => 'lib',
            'password' => Hash::make('password')
        ]);
        $lib->assignRole('Librarian');

        $mohamed = User::firstOrCreate([
            'email' => 'mohamed@gmail.com'
        ], [
            'name' => 'mohamed',
            'password' => Hash::make('password')
        ]);
        $mohamed->assignRole('Member');
    }
}
