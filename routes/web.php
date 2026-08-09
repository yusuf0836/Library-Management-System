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
use App\Http\Controllers\BookIssueController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\MemberBorrowingController;
use App\Http\Controllers\ReportController;

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

    Route::get('book-issues', [BookIssueController::class, 'index'])
    ->name('book-issues.index');

    Route::get('book-issues/create', [BookIssueController::class, 'create'])
        ->name('book-issues.create');

    Route::post('book-issues', [BookIssueController::class, 'store'])
        ->name('book-issues.store');

    Route::post('book-issues/{bookIssue}/return', [BookIssueController::class, 'returnBook'])
        ->name('book-issues.return');

    Route::get('fines', [FineController::class, 'index'])
    ->name('fines.index');

    Route::post('fines/{fine}/pay', [FineController::class, 'pay'])
        ->name('fines.pay');

    Route::get('reports/overdue', [ReportController::class, 'overdue'])
        ->name('reports.overdue');
});

Route::middleware(['auth', 'role:member'])->group(function () {
    Route::get('my-borrowings', [MemberBorrowingController::class, 'index'])
        ->name('member.borrowings');
});
