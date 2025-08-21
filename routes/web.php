<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Login route must be public
Volt::route('login', 'auth.login')->name('login');

Route::redirect('/dashboard', '/login')->name('dashboard.fallback');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth', 'user_type:admin'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});

Route::middleware(['auth', 'user_type:preasses,encoding'])->group(function () {
    // 👆 allow both preasses and encoding
    Route::view('/qsf/dashboard', 'qsf.dashboard')->name('qsf.dashboard');
});

require __DIR__ . '/auth.php';
