<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookCopyController;
use App\Http\Controllers\MemberController;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'role:admin,librarian'])->group(function () {
    Route::resource('categories', CategoryController::class)
        ->except(['show']);

    Route::resource('authors', AuthorController::class)
        ->except(['show']);

    Route::resource('publishers', PublisherController::class)
    ->except(['show']);

    Route::resource('books', BookController::class)
    ->except(['show']);

    Route::resource('book-copies', BookCopyController::class)
    ->parameters(['book-copies' => 'bookCopy'])
    ->except(['show']);

    Route::resource('members', MemberController::class)
    ->except(['show']);
});
