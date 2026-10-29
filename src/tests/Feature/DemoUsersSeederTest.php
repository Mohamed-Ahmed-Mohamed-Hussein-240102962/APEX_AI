<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DemoUsersSeeder;
use Spatie\Permission\Models\Role;

class DemoUsersSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_users_seeder_creates_users_successfully(): void
    {
        // 1. Create every role the seeder assigns (the test DB starts empty)
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Librarian']);
        Role::create(['name' => 'Member']);

        // 2. Run the seeder
        $this->seed(DemoUsersSeeder::class);

        // 3. Verify the data
        $this->assertDatabaseCount('users', 3);

        $this->assertDatabaseHas('users', ['email' => 'admin@gmail.com']);
        $this->assertDatabaseHas('users', ['email' => 'lib@gmail.com']);
        $this->assertDatabaseHas('users', ['email' => 'mohamed@gmail.com']);
    }
}