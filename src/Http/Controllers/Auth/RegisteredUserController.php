<?php

namespace BladeCN\BladeCN\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return view('bladecn::auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // The app's user model, as configured for the `users` auth provider.
        /** @var class-string<Model&Authenticatable> $userModel */
        $userModel = config('auth.providers.users.model', 'App\\Models\\User');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.$userModel],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = $userModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }
}
