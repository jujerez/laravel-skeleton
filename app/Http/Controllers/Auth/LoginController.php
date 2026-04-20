<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => __('auth.failed')]);
        }

        $request->session()->regenerate();

        $destination = Auth::user()->hasRole('client') ? route('welcome') : route('dashboard');

        return redirect()->intended($destination);
    }

    /**
     * Handle the logout request.
     */
    public function destroy(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('welcome');
    }
}
