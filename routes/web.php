<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatRoomController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReplyCommentController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HiddenPostController;

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

// Rute halaman depan (redirect ke login)
Route::get('/', function () {
    return redirect()->route('login'); // Gantilah 'login' dengan nama route untuk halaman login Anda
});

// Rute otentikasi
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('register', [AuthController::class, 'showRegister'])->name('register');
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Rute yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {

    // Rute biodata
    Route::get('/biodata/create', [DashboardController::class, 'create'])->name('biodata.create');
    Route::post('/biodata', [DashboardController::class, 'store'])->name('biodata.store');
    Route::get('/biodata/{id}/edit', [DashboardController::class, 'edit'])->name('biodata.edit');
    Route::put('/biodata/{id}', [DashboardController::class, 'update'])->name('biodata.update');

    // Rute pengguna
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/search', [UserController::class, 'search'])->name('user.search');
    Route::get('/user/profile/{id}', [UserController::class, 'profile'])->name('user.profile');
    Route::get('/myprofile', [UserController::class, 'myProfile'])->name('user.myprofile');
    Route::post('/follow/{id}', [UserController::class, 'follow'])->name('user.follow');
    Route::post('/unfollow/{id}', [UserController::class, 'unfollow'])->name('user.unfollow');
    Route::get('/user/{id}/followers', [UserController::class, 'followers'])->name('user.followers');
    Route::get('/user/{id}/following', [UserController::class, 'following'])->name('user.following');

    // Rute chat
    Route::get('/chat', [ChatRoomController::class, 'index'])->name('chat.index');
    Route::post('/chat/store', [ChatRoomController::class, 'store'])->name('chat.store');
    Route::get('/chat/{id}', [ChatRoomController::class, 'show'])->name('chat.show');
    Route::post('/chat/{chatRoomId}/message', [MessageController::class, 'store'])->name('message.store');
    Route::get('/chat/create/{user_id}', [ChatRoomController::class, 'create'])->name('chat.create');

    // Rute postingan
    Route::get('/posts/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/posts', [PostController::class, 'store'])->name('post.store');
    Route::post('/posts/{id}/like', [PostController::class, 'like'])->name('post.like');
    Route::post('/posts/{id}/comment', [PostController::class, 'addComment'])->name('post.comment');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

    Route::post('/post/{id}/hide', [HiddenPostController::class, 'hide'])->name('post.hide');
    Route::post('/post/{id}/unhide', [HiddenPostController::class, 'unhide'])->name('post.unhide');
    Route::delete('/posts/{id}/unhide', [PostController::class, 'unhide'])->name('posts.unhide');
    Route::get('/hide-post', [HiddenPostController::class, 'index'])->name('hide.posts');

    // Rute pengaturan
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/logout', [SettingsController::class, 'logout'])->name('logout');

    Route::post('/comments/reply', [ReplyCommentController::class, 'store'])->name('reply-comments.store');
    Route::post('posts/{post_id}/reply/{comment_id}', [PostController::class, 'reply'])->name('post.reply');

    Route::get('/settings/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/settings/change-password', [PasswordController::class, 'updatePassword'])->name('password.update');

    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
    Route::get('/user/{id}', [UserController::class, 'showUserProfile'])->name('user.profile.show');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
});

// Rute untuk mengedit, memperbarui, dan menghapus postingan (periksa rute jika terdapat rute duplikat)
Route::middleware(['auth'])->group(function () {
    Route::get('/post/{post}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post}', [PostController::class, 'destroy'])->name('post.destroy');
});
