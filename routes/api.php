<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::get('/list-users', function () {

    $users = User::all();

    return response()->json($users, 200);
});

