<?php

namespace App\Http\Controllers;

use App\Http\Requests\Login\LoginRequest;
use App\Services\Login\LoginService;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{
    public function __construct(private readonly LoginService $loginService)
    {
    }

    /**
     * Get the login view.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function loginPage(): \Illuminate\Contracts\View\View
    {
        return View::make('login');
    }

    /**
     * Perform the login.
     *
     * @param LoginRequest $loginRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $loginRequest): \Illuminate\Http\RedirectResponse
    {
        if (!$this->loginService->login($loginRequest->email, $loginRequest->password)) {
            return redirect()->back()->with('error', 'Invalid login credentials');
        }

        return redirect()->route('admin.products.index');
    }

    /**
     * Perform the logout.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(): \Illuminate\Http\RedirectResponse
    {
        $this->loginService->logout();

        return redirect()->route('login');
    }
}
