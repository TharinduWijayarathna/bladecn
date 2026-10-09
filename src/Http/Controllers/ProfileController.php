<?php

namespace BladeCN\BladeCN\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        // Prefer the view published into the app, then the package's own.
        return view()->first(['settings.profile', 'bladecn::settings.profile', 'bladecn::profile']);
    }

    /**
     * Display the password form.
     */
    public function editPassword(Request $request)
    {
        // Prefer the view published into the app, then the package's own.
        return view()->first(['settings.password', 'bladecn::settings.password']);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
            'avatar' => ['nullable', 'image', 'max:2048'], // 2MB max
        ]);

        $user = $request->user();
        $user->fill($validated);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            $current = $user->getAttribute('avatar');

            if ($current && Storage::disk('public')->exists($current)) {
                Storage::disk('public')->delete($current);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->setAttribute('avatar', $path);
        }

        if ($user->isDirty('email')) {
            $user->setAttribute('email_verified_at', null);
        }

        $user->save();

        return back()->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
