<?php

namespace Tests\Feature\Login;

use Tests\Feature\BaseTest;

class LoginTest extends BaseTest
{
    public function test_login(): void
    {
        // Check that user is not logged in.
        $this->assertFalse(auth()->check());

        $response = $this
            ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->post('/login', [
                'email' => $this->user->email,
                'password' => 'password',
            ]);

        $response->assertStatus(302);

        // Check that user is logged in.
        $this->assertTrue(auth()->check());
    }

    public function test_login_wrong_email(): void
    {
        // Check that the user is not logged in.
        $this->assertFalse(auth()->check());

        $response = $this
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
            ])
            ->post('/login', [
                'email' => 'not-existing@sample.com',
                'password' => 'password',
            ]);

        $response->assertStatus(302);

        // Check that the user is still not logged in.
        $this->assertFalse(auth()->check());
    }

    public function test_login_wrong_password(): void
    {
        // Check that the user is not logged in.
        $this->assertFalse(auth()->check());

        $response = $this
            ->withHeaders([
                'X-CSRF-TOKEN' => csrf_token(),
            ])
            ->post('/login', [
                'email' => $this->user->email,
                'password' => 'wrong-password',
            ]);

        $response->assertStatus(302);

        // Check that the user is still not logged in.
        $this->assertFalse(auth()->check());
    }

    public function test_logout(): void
    {
        // Log user in.
        $response = $this
            ->withHeaders(['X-CSRF-TOKEN' => csrf_token()])
            ->post('/login', [
                'email' => $this->user->email,
                'password' => 'password',
            ]);

        // Check that the user is logged in.
        $this->assertTrue(auth()->check());

        // Perform the logout.
        $response = $this->get('/logout');

        $response->assertStatus(302);

        // Check that the user is logged out.
        $this->assertFalse(auth()->check());
    }
}
