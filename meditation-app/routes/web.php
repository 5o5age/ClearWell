<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/', fn() => view('home'))->name('home');
Route::get('/audio',  fn() => view('audio.index'))->name('audio.index');
Route::get('/video',  fn() => view('video.index'))->name('video.index');
Route::get('/pages',  fn() => view('pages.index'))->name('pages.index');
Route::get('/games',  fn() => view('games.index'))->name('games.index');
Route::get('/games/{slug}', fn(string $slug) => view('games.show', ['slug' => $slug]))->name('games.show');
