<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiHealthTest extends TestCase
{
    public function test_api_health_endpoint_is_healthy(): void
    {
        $response = $this->getJson('/api/health');

        $response->assertStatus(200);
    }
}
