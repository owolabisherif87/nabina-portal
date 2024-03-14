<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserAccountController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(): Response
    {
        return Inertia::render('User/Index', []);
    }

    public function show() {
        $users = User::all();

        return response()->json($users, 200);
    }

    public function store(Request $request) {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => ['required','string','lowercase','email','max:255',Rule::unique("users")->ignore($request->id)],
            "role" => 'required|string|lowercase',
            "position" => 'required|string',
            "image" => 'nullable|image',
            'password' => [Rule::requiredIf(fn() => $request->isMethod("POST"))],
        ]);

        $image = "assets/placeholder.png";

        if ($request->hasFile("image")) {
            $path = $request->file('image')->store('profile', 'public');

            $image = Storage::url($path);
        }

        $user = array();

        if($request->isMethod("POST")) {
            $user = User::create([
                'name' => $request->lastname . " " . $request->firstname,
                'email' => $request->email,
                'role' => $request->role,
                'position' => $request->position,
                "image" => $image,
                'password' => Hash::make($request->password),
            ]);
        }

        if($request->isMethod("PUT")) {
            $user = User::find($request->id);
            $user->name = $request->lastname . " " . $request->firstname;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->position = $request->position;
            $user->image = $request->hasFile("image") ? $image : $user->image;
            $user->password = isset($request->password) ? Hash::make($request->password) : $user->password;

            $user->save();
        }

        return redirect()->back();
    }


    public function delete($id) {
        $user = User::find($id);

        $user->delete();
    }
}
