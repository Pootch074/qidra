<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PreassessController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/preassess', function () {
    return view('preassess');
})->name('preassess');


// Route::get('/welcome', function () {
//     return view('welcome');
// })->name('welcome')->middleware('auth');

// Route::get('/', [AuthenticatedSessionController::class, 'create'])
//         ->name('login');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
