<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HealthEndpointTest extends TestCase
{
    public function test_health_endpoint_returns_ok(): void
    {
        $this->getJson('/api/health')
            ->assertOk()
            ->assertJson(['status' => 'ok']);
    }

    public function test_ready_endpoint_returns_ready_when_database_is_up(): void
    {
        $this->getJson('/api/ready')
            ->assertOk()
            ->assertJson(['status' => 'ready', 'database' => 'up']);
    }

    public function test_ready_endpoint_returns_503_when_database_is_down(): void
    {
        DB::shouldReceive('connection')->andThrow(new \RuntimeException('database down'));

        $this->getJson('/api/ready')
            ->assertStatus(503)
            ->assertJson(['status' => 'unavailable', 'database' => 'down']);
    }

    public function test_health_endpoints_are_public(): void
    {
        $this->getJson('/api/health')->assertStatus(200);
        $this->getJson('/api/ready')->assertStatus(200);
    }
}
