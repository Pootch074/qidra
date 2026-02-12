<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\IdscanController;
use App\Http\Controllers\PacdController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\StepsController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WindowsController;
use App\Http\Middleware\CheckUserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Session Checking Route
|--------------------------------------------------------------------------
*/

Route::get('/session/check', function () {
    $user = Auth::user();

    if (! $user) {
        return response()->json(['active' => false]);
    }

    if ($user->session_id !== session()->getId()) {
        Auth::logout();
        session()->invalidate();

        return response()->json(['active' => false]);
    }

    return response()->json(['active' => true]);
})->name('session.check');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn () => Auth::check() ? redirect()->intended() : redirect(route('login')));

Route::prefix('auth')->group(function () {
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
    Route::get('/sections/{divisionId}', [RegisterController::class, 'sectionsByDivision'])->name('sections.byDivision');
    Route::get('/steps/{sectionId}', [RegisterController::class, 'stepsBySection'])->name('steps.bySection');
    Route::get('/categories/{stepId}', [RegisterController::class, 'categoriesByStep'])->name('categories.byStep');
    Route::get('/windows/{categoryId}', [RegisterController::class, 'windowsByCategory'])->name('windows.byCategory');

    Route::get('/register-verify-otp', [RegisterController::class, 'registerShowOtp'])->name('register.show.otp');
    Route::post('/register-verify-otp', [RegisterController::class, 'registerVerifyOtp'])->name('register.verify.otp');
    Route::post('/register/resend-otp', [RegisterController::class, 'resendOtp'])->name('register.resend.otp');

    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
    Route::get('/login-verify-otp', [LoginController::class, 'loginShowOtp'])->name('login.show.otp');
    Route::post('/login-verify-otp', [LoginController::class, 'loginVerifyOtp'])->name('login.verify.otp');
    Route::post('/login/resend-otp', [LoginController::class, 'resendOtp'])->name('login.resend.otp');
    Route::post('/login/otp/cancel', [LoginController::class, 'cancelOtp'])->name('login.otp.cancel');
    Route::post('/check-email', [LoginController::class, 'checkEmail']);

});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Generic)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/auto-logout', [LoginController::class, 'logout'])->name('auto.logout');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes by User Type
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'otp.verified', CheckUserType::class.':0,1,2,3,5,6'])->group(function () {
    // Route::middleware(['auth', 'otp.verified', CheckUserType::class.':0,1,2,3,5,6'])->group(function () {
    Route::get('superadmin', [SuperAdminController::class, 'index'])->name('superadmin');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::get('admin', [AdminController::class, 'index'])->name('admin');

    Route::prefix('admin/active-users')->group(function () {
        Route::get('/', [AdminController::class, 'activeUsers'])->name('admin.activeUsers');
        Route::get('/json', [AdminController::class, 'usersJson'])->name('admin.users.json');
        Route::post('/store', [AdminController::class, 'store'])->name('admin.storeUsers');
        Route::delete('/{user}', [AdminController::class, 'destroy'])->name('admin.destroyUsers');
        Route::post('/{user}/update-assignment', [AdminController::class, 'updateAssignment'])->name('admin.users.updateAssignment');
    });
    Route::prefix('admin/pending-users')->group(function () {
        Route::get('/', [AdminController::class, 'pendingUsers'])->name('admin.pendingUsers');
        Route::put('/users/{user}/update-status', [AdminController::class, 'updateStatus'])->name('admin.users.updateStatus');
        Route::get('/steps/{step}/windows', [AdminController::class, 'getWindowsByStep'])->name('admin.steps.windows');

    });
    Route::prefix('admin/steps')->group(function () {
        Route::get('/', [AdminController::class, 'fetchSteps'])->name('fetch.steps');
        Route::post('/', [AdminController::class, 'storeStep'])->name('store.step');
    });

    Route::get('idscan', [IdscanController::class, 'index'])->name('idscan');

    Route::get('pacd', [PacdController::class, 'index'])->name('pacd');
    Route::prefix('pacd')->group(function () {
        Route::post('/generate/{section}', [PacdController::class, 'generateQueue'])->name('pacd.generate');
        Route::get('/transactions/table', [PacdController::class, 'transactionsTable'])->name('pacd.transactions.table');
        Route::get('/sections/cards', [PacdController::class, 'sectionsCards'])->name('pacd.sections.cards');
        Route::get('/pending/table', [PacdController::class, 'pendingQueues'])->name('pacd.pending.table');
        Route::get('/clients/table', [PacdController::class, 'clientsTable'])->name('pacd.clients.table');
        Route::post('/pacd/store-client', [PacdController::class, 'storeClient'])->name('pacd.storeClient');
    });

    Route::get('user', [UsersController::class, 'index'])->name('user');
    Route::get('display', [DisplayController::class, 'index'])->name('display');
});

Route::prefix('admin/steps')->group(function () {
        Route::get('/', [StepsController::class, 'steps'])->name('admin.steps');
        Route::post('/', [StepsController::class, 'store'])->name('steps.store');
    });

Route::middleware(['auth'])->group(function () {
    // === Users Management ===

    Route::put('/steps/{id}', [StepsController::class, 'update'])->name('steps.update');
    Route::delete('/steps/{id}', [StepsController::class, 'destroy'])->name('steps.destroy');
    Route::get('/steps/check/{sectionId}/{stepNumber}', [StepsController::class, 'check']);
    Route::get('/steps/check-name/{sectionId}/{stepName}', [StepsController::class, 'checkName']);

    // === Windows Management ===
    Route::prefix('admin/windows')->group(function () {
        Route::get('/', [WindowsController::class, 'index'])->name('admin.windows');
        Route::post('/', [WindowsController::class, 'store'])->name('windows.store');
        Route::delete('/{id}', [WindowsController::class, 'destroy'])->name('windows.destroy');
    });
    Route::get('/windows/check/{stepId}/{windowNumber}', [WindowsController::class, 'check'])->name('windows.check');

    // === Display Routes ===
    Route::get('/steps', [DisplayController::class, 'getStepsBySectionId'])->name('steps');
    Route::get('/display/transactions/latest', [DisplayController::class, 'getLatestTransaction'])->name('display.latest-transaction');

    Route::post('/transactions/{id}/resume', [PacdController::class, 'resumeTransaction'])->name('transactions.resume');

    // === Queue Operations ===
    Route::post('/queue/store', [TransactionsController::class, 'store'])->name('queue.store');
    Route::post('/queue/skip', [UsersController::class, 'skipQueue'])->name('users.skipQueue');
    Route::post('/queue/recall', [UsersController::class, 'recallQueue'])->name('users.recallQueue');
    Route::post('/queue/proceed', [UsersController::class, 'proceedQueue'])->name('users.proceedQueue');

    // === User Actions ===
    Route::post('/users/next-regular', [UsersController::class, 'nextRegular'])->name('users.nextRegular');
    Route::post('/users/next-priority', [UsersController::class, 'nextPriority'])->name('users.nextPriority');
    Route::post('/users/next-returnee', [UsersController::class, 'nextReturnee'])->name('users.nextReturnee');
    Route::post('/users/returnQueue', [UsersController::class, 'returnQueue'])->name('users.returnQueue');

    // === Queues Data ===
    Route::get('/queues/data', [UsersController::class, 'getQueues'])->name('queues.data');
    Route::get('/windows/by-step/{step}', [UsersController::class, 'getWindowsByStep'])->name('windows.byStep');
    Route::post('/queues/serve-again', [UsersController::class, 'serveAgain'])->name('queues.serveAgain');

    // === Upcoming Queues ===
    Route::prefix('queues/upcoming')->group(function () {
        Route::post('/preassess/regu/update', [UsersController::class, 'updateUpcomingPreassessRegu'])->name('queues.updateUpcomingPreassessRegu');
        Route::post('/prio/update', [UsersController::class, 'updateUpcomingPrio'])->name('queues.updateUpcomingPrio');
        Route::post('/returnee/update', [UsersController::class, 'updateUpcomingReturnee'])->name('queues.updateUpcomingReturnee');
        Route::post('/updateUpcoming', [UsersController::class, 'updateUpcoming'])->name('queues.updateUpcoming');
    });

    // === Pending Queues ===
    Route::prefix('queues/pending')->group(function () {
        Route::post('/regu/update', [UsersController::class, 'updatePendingRegu'])->name('queues.updatePendingRegu');
        Route::post('/prio/update', [UsersController::class, 'updatePendingPrio'])->name('queues.updatePendingPrio');
        Route::post('/returnee/update', [UsersController::class, 'updatePendingReturnee'])->name('queues.updatePendingReturnee');
    });

    // === SuperAdmin Actions ===
    Route::post('superadmin/store', [SuperAdminController::class, 'store'])->name('superadmin.store');
});

Route::get('/forgot-password', [PasswordController::class, 'passwordRequest'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'passwordEmail'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [PasswordController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'updatePassword'])->middleware('guest')->name('password.update');
