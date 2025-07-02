<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BaseTest extends TestCase
{
    use RefreshDatabase;
    protected User $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
