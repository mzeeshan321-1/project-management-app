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

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile image.
     */
    public function updateImage(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = User::find($id);

        if (!file_exists(public_path('images'))) {
            mkdir(public_path('images'), 0755, true); // Create the directory if it doesn't exist
        }
        if ($request->hasFile('image')) {
            $userImage = $user->image;
            $imageName = null;
            if (!empty($userImage)) {
                $existingImage = public_path('images/' . $userImage);
                if (file_exists($existingImage)) {
                    unlink($existingImage);
                }
            }
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $sanitizedOriginalName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $originalName);
            $imageName = $sanitizedOriginalName . '_' . time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('images');
            $image->move($destinationPath, $imageName);
        } else {
            $imageName = $user->image;
        }

        $user->update(['image' => $imageName]);

        return redirect()->route('profile.index');
    }
}
