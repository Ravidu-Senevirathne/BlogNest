<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Auth\SocialAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/**
 * Public Routes
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Authentication Routes
 */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/**
 * Role-Based Dashboard Routes
 */
// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Editor routes
Route::middleware(['auth', 'role:editor'])->group(function () {
    Route::get('/editor/dashboard', function () {
        return view('editor.dashboard');
    })->name('editor.dashboard');
});

// Reader routes
Route::middleware(['auth', 'role:reader'])->group(function () {
    Route::get('/reader/dashboard', function () {
        return view('reader.dashboard');
    })->name('reader.dashboard');
});

/**
 * User Profile Routes
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Admin Panel Routes
 */
Route::prefix('admin')->group(function () {
    // Guest routes for admin
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [AuthController::class, 'login'])->name('admin.login.submit');
    });

    // Authenticated admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');
    });
});

/**
 * Social Authentication Routes
 */
// Provider redirect and callback
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider'])
    ->name('social.redirect')
    ->where('provider', 'github|google');

Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])
    ->name('social.callback')
    ->where('provider', 'github|google');

// Role selection after social authentication
Route::get('/auth/role-selection', [SocialAuthController::class, 'showRoleSelectionForm'])
    ->name('social.role.selection');

Route::post('/auth/role-selection', [SocialAuthController::class, 'storeUserWithRole'])
    ->name('social.role.store');

// Include authentication routes
require __DIR__ . '/auth.php';
