<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Models\Tanent;
use App\Models\Client;
use App\Models\Expert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the authenticated user's profile.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load(['tanent.user', 'expert', 'client']);
        
        return view('profile.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Display a specific user's profile (for authorized users).
     */
    public function show(Request $request, $id): View
    {
        $authUser = $request->user();
        
        // Find the user whose profile is being requested
        $user = User::with(['roles', 'tanent.user', 'expert', 'client'])->findOrFail($id);
        
        // Check if the authenticated user can view this profile
        if ($authUser->hasRole('super-admin')) {
            // Super admin can see all profiles
            return view('profile.edit', [
                'user' => $user,
            ]);
        } elseif ($authUser->hasRole('middleman')) {
            // Middleman can see their own experts and clients
            $tanent = $authUser->tanent;
            
            // Check if the user is an expert or client of this middleman
            if (($user->expert && $user->expert->tanent_id == $tanent->id) ||
                ($user->client && $user->client->tanent_id == $tanent->id)) {
                return view('profile.edit', [
                    'user' => $user,
                ]);
            }
        }
        
        // If not authorized, show their own profile
        return redirect()->route('profile.edit');
    }

    /**
     * Display a list of profiles based on user role.
     */
    public function index(Request $request): View
    {
        $authUser = $request->user();
        
        return view('profile.index', compact('authUser'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
