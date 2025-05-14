<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendAuthController;
use App\Http\Controllers\FrontendUserController;

Route::get('/', function () {
    if (Illuminate\Support\Facades\Session::has('api_token')) {
        return redirect()->route('frontend.users.index');
    }
    return redirect()->route('frontend.login.form');
});

// Frontend Auth Routes
Route::name('frontend.')->group(function () {
    Route::get('/login', [FrontendAuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [FrontendAuthController::class, 'login'])->name('login');
    Route::get('/register', [FrontendAuthController::class, 'showRegistrationForm'])->name('register.form');
    Route::post('/register', [FrontendAuthController::class, 'register'])->name('register');
    Route::post('/logout', [FrontendAuthController::class, 'logout'])->name('logout')->middleware('frontend.auth'); // Protect logout
});


// Frontend User CRUD Routes (Protected by frontend.auth middleware)
Route::name('frontend.users.')->prefix('users')->middleware('frontend.auth')->group(function () {
    Route::get('/', [FrontendUserController::class, 'index'])->name('index');
    Route::get('/create', [FrontendUserController::class, 'create'])->name('create');
    Route::post('/', [FrontendUserController::class, 'store'])->name('store');
    Route::get('/{id}', [FrontendUserController::class, 'show'])->name('show'); // Optional
    Route::get('/{id}/edit', [FrontendUserController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FrontendUserController::class, 'update'])->name('update');
    Route::delete('/{id}', [FrontendUserController::class, 'destroy'])->name('destroy');
});
