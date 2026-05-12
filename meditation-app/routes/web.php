<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AudioController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('home'))->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/audio', [AudioController::class, 'index'])->name('audio.index');
Route::get('/video', [VideoController::class, 'index'])->name('video.index');
Route::get('/pages', [PostController::class, 'index'])->name('pages.index');
Route::get('/pages/{post}', [PostController::class, 'show'])->name('pages.show');

Route::get('/games', fn() => view('games.index'))->name('games.index');
Route::get('/games/{slug}', fn(string $slug) => view('games.show', ['slug' => $slug]))->name('games.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/audios/create', [AudioController::class, 'create'])->name('audios.create');
    Route::post('/audios', [AudioController::class, 'store'])->name('audios.store');
    Route::get('/audios/{audio}/edit', [AudioController::class, 'edit'])->name('audios.edit');
    Route::patch('/audios/{audio}', [AudioController::class, 'update'])->name('audios.update');
    Route::delete('/audios/{audio}', [AudioController::class, 'destroy'])->name('audios.destroy');

    Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->name('videos.edit');
    Route::patch('/videos/{video}', [VideoController::class, 'update'])->name('videos.update');
    Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');

    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
});

require __DIR__.'/auth.php';
