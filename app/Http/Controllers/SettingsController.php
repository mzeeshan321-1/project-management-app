<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index(Request $request): View
    {
        if (!auth()->user()->hasPermissionTo('manage settings')) {
            abort(403, 'You do not have permission to view this page.');
        }
        return view('settings.index', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user settings.
     */
    public function update(Request $request): RedirectResponse
    {
        if (!auth()->user()->hasPermissionTo('manage settings')) {
            abort(403, 'You do not have permission to view this page.');
        }
        $user = $request->user();

        // Handle profile update
        if ($request->has('update_profile')) {
            $validated = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'contact' => ['nullable', 'string', 'max:255'],
                'address' => ['nullable', 'string', 'max:255'],
            ], [], [
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email Address',
                'contact' => 'Contact Number',
                'address' => 'Address',
            ]);

            $user->update($validated);

            return redirect()->route('settings.index')->with('status', 'profile-updated');
        }

        // Handle password update
        if ($request->has('update_password')) {
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);

            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            return redirect()->route('settings.index')->with('status', 'password-updated');
        }

        // Default redirect
        return redirect()->route('settings.index')->with('status', 'settings-updated');
    }
}