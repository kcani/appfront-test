<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendPriceChangeNotification;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        if (Auth::attempt($request->except('_token'))) {
            return redirect()->route('admin.products');
        }

        return redirect()->back()->with('error', 'Invalid login credentials');
    }

    /**
     * Perform the logout.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
