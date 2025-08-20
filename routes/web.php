<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Login route must be public
Volt::route('login', 'auth.login')->name('login');

// General dashboard (optional, remove if only role dashboards exist)
Route::view('dashboard', 'dashboard')
    ->middleware(['auth'])
    ->name('dashboard');

// Authenticated settings pages
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

// Role-based dashboards
Route::middleware(['auth', 'user_type:admin'])->group(function () {
    Route::view('/admin/dashboard', 'admin.dashboard')->name('admin.dashboard');
});

Route::middleware(['auth', 'user_type:staff'])->group(function () {
    Route::view('/staff/dashboard', 'staff.dashboard')->name('staff.dashboard');
});

require __DIR__.'/auth.php';
