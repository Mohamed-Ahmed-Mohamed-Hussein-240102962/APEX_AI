<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DemoUsersSeeder;
use Spatie\Permission\Models\Role;

class DemoUsersSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_users_seeder_creates_users_with_roles_successfully(): void
    {
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Librarian']);
        Role::firstOrCreate(['name' => 'Member']);

        $this->seed(DemoUsersSeeder::class);

        $this->assertDatabaseCount('users', 3);

        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'librarian@example.com']);
        $this->assertDatabaseHas('users', ['email' => 'member@example.com']);
    }
}
