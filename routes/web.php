<?php

use App\Http\Controllers\HumanResourceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAccountController;
use App\Http\Middleware\Permission;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect("/login");
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', Permission::class])->group(function () {
    Route::delete('/user/delete/{id}', [UserAccountController::class, 'delete'])->name('user.delete');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/user-account', [UserAccountController::class, 'index'])->name('user-account');
    Route::get('/user-list', [UserAccountController::class, 'show'])->name('user-list');
    Route::match(['put', 'post'], '/user-account/register', [UserAccountController::class, 'store'])->name('user-account-register');
    Route::get('/human-ressource', [HumanResourceController::class, 'index'])->name('human-resource');
});

require __DIR__.'/auth.php';
