<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // استخدام firstOrCreate عشان لو الروتس موجودة ميعملش إيرور
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Librarian']);
        Role::firstOrCreate(['name' => 'Member']);
    }
}