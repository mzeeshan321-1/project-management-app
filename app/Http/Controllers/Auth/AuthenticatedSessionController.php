<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            // Update last login
            $user = Auth::user();
            $user->update([
                'last_login' => now()
            ]);

            flash()->options([
                'timeout' => 3000,
                'position' => 'bottom-center',
            ])->success('Login successful! Welcome back: ' . $user->first_name . '.');

            return redirect()->intended(route('dashboard', absolute: false));           
        } catch (\Exception $e) {
            flash()->error('Login failed: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        flash()->options([
            'timeout' => 3000,
            'position' => 'bottom-center',
        ])->success('You have been logged out successfully.');
        return redirect('/');
    }
}
