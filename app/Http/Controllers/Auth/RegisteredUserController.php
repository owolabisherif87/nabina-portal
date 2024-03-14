<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            "role" => 'required|string|lowercase',
            "position" => 'required|string',
            "image" => 'nullable|image',
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $image = "assets/placeholder.png";

        if($request->hasFile("image")) {
            $image = $request->file('image')->store('profile');
        }

        $user = User::create([
            'name' => $request->lastname . " " . $request->firstname,
            'email' => $request->email,
            'role' => $request->role,
            'position' => $request->position,
            "image" => $image,
            'password' => Hash::make($request->password),
        ]);

        // event(new Registered($user));

        // Auth::login($user);

        // return redirect(route('dashboard', absolute: false));

        return redirect()->back();
    }
}
