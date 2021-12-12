<?php

namespace Tests\Unit;

use Tests\TestCase;

class UserTest extends TestCase
{

    public function test_register()
    {
        $data = [
            'email' => 'test3@test.com',
            'password' => 'test',
            'name' => 'Test'
        ];

        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(201);
    }

    public function test_login()
    {
        $data = [
            'email' => 'test2@test.com',
            'password' => 'test'
        ];

        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200);
    }
}
