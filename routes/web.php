<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Editor\PostController;

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
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Blog routes
Route::get('/reader/blogs', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');

/**
 * Authentication Routes
 */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('editor')) {
            return redirect()->route('editor.dashboard');
        } else {
            return redirect()->route('reader.dashboard');
        }
    })->name('dashboard');
});


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

    // Post management routes
    Route::resource('/editor/posts', PostController::class, [
        'as' => 'editor'
    ]);
});

// Reader routes
Route::middleware(['auth', 'role:reader|editor|admin'])->prefix('reader')->name('reader.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Reader\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookmarks', [App\Http\Controllers\Reader\DashboardController::class, 'bookmarks'])->name('bookmarks');
    Route::get('/search', [App\Http\Controllers\Reader\DashboardController::class, 'search'])->name('search');

    // Post interaction routes
    Route::post('/posts/{post}/comments', [App\Http\Controllers\Reader\CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [App\Http\Controllers\Reader\CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/posts/{post}/like', [App\Http\Controllers\Reader\LikeController::class, 'toggle'])->name('like.toggle');
    Route::post('/posts/{post}/bookmark', [App\Http\Controllers\Reader\BookmarkController::class, 'toggle'])->name('bookmark.toggle');
});


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
        Route::get('dashboard', [AnalyticsController::class, 'index'])->name('admin.dashboard');
        Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

        // User management
        Route::resource('users', UserController::class, ['as' => 'admin']);
        Route::post('users/{user}/roles', [UserController::class, 'updateRoles'])->name('admin.users.roles');

        // Post management
        Route::resource('posts', AdminPostController::class, ['as' => 'admin']);
        Route::post('posts/{post}/approve', [AdminPostController::class, 'approve'])->name('admin.posts.approve');
        Route::post('posts/{post}/reject', [AdminPostController::class, 'reject'])->name('admin.posts.reject');

        // Category management
        Route::resource('categories', CategoryController::class, ['as' => 'admin']);

        // Tag management
        Route::resource('tags', TagController::class, ['as' => 'admin']);
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
