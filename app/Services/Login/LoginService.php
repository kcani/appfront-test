<?php

namespace App\Services\Login;

use Illuminate\Support\Facades\Auth;

class LoginService
{
    /**
     * Attempt the login and session create by the given credentials.
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password): bool
    {
        return Auth::attempt([
            'email' => $email,
            'password' => $password,
        ]);
    }

    /**
     * Devalidate the active session.
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }
}
