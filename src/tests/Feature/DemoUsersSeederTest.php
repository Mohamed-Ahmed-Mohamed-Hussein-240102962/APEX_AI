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
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Librarian']);
        Role::create(['name' => 'Member']);

        $this->seed(DemoUsersSeeder::class);

        $this->assertDatabaseCount('users', 3);

        $this->assertDatabaseHas('users', ['email' => 'admin@gmail.com']);
        $this->assertDatabaseHas('users', ['email' => 'lib@gmail.com']);
        $this->assertDatabaseHas('users', ['email' => 'mohamed@gmail.com']);
    }
}
