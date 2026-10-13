<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BuddyRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PriceReportController;
use App\Http\Controllers\ThreadController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Go Hunter Super-App
|--------------------------------------------------------------------------
*/

// Authentication (Login, Register, Logout)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// 1. Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Direktori Destinasi
Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index');
Route::get('/destinations/{destination:slug}', [DestinationController::class, 'show'])->name('destinations.show');

// 3. Price Reports (Crowdsourced Price Checker / Anti-Getok)
Route::post('/destinations/{destination:slug}/price-reports', [PriceReportController::class, 'store'])
    ->name('destinations.price-reports.store');

// 4. Travel Buddy (/travel-buddy & alias /buddy-requests)
Route::resource('travel-buddy', BuddyRequestController::class)
    ->parameters(['travel-buddy' => 'buddyRequest'])
    ->names('travel-buddy');

Route::resource('buddy-requests', BuddyRequestController::class)->only(['index', 'create', 'store', 'show']);
Route::get('/buddy', [BuddyRequestController::class, 'index'])->name('buddy.index');

// 5. Forum Q&A Situasional (/forum & alias /threads)
Route::resource('forum', ThreadController::class)
    ->parameters(['forum' => 'thread'])
    ->names('forum');
Route::post('/forum/{thread:slug}/comments', [CommentController::class, 'store'])->name('forum.comments.store');

Route::resource('threads', ThreadController::class)->only(['index', 'create', 'store', 'show']);
Route::post('/threads/{thread:slug}/comments', [CommentController::class, 'store'])->name('threads.comments.store');
Route::post('/comments/{comment}/solution', [CommentController::class, 'markAsSolution'])->name('comments.mark-as-solution');
