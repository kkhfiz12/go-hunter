<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BuddyRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EksplorPediaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PriceReportController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\TripyController;
use App\Http\Controllers\UrbanRallyController;
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

// 1. Home & Dashboard
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

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

// 6. Modul TRIPY (Peta Eksplorasi Kota & Rekomendasi Bertingkat)
Route::get('/tripy', [TripyController::class, 'index'])->name('tripy.index');
Route::get('/tripy/cities/{city:slug}', [TripyController::class, 'cityData'])->name('tripy.cities.data');
Route::get('/tripy/destinations/{destination:id}/spots', [TripyController::class, 'spots'])->name('tripy.destinations.spots');
Route::post('/tripy/spots/{spot:id}/checkin', [TripyController::class, 'checkin'])->name('tripy.spots.checkin');

// 7. Modul EKSPLOR PEDIA (Edukasi, Kuis Bertingkat & Leaderboard)
Route::prefix('eksplor-pedia')->name('eksplor-pedia.')->group(function () {
    Route::get('/', [EksplorPediaController::class, 'index'])->name('index');
    Route::get('/leaderboard', [EksplorPediaController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/rewards', [EksplorPediaController::class, 'rewards'])->name('rewards');
    Route::post('/rewards/{reward}/redeem', [EksplorPediaController::class, 'redeem'])
        ->middleware('auth')
        ->name('redeem');
    Route::get('/history', [EksplorPediaController::class, 'history'])
        ->middleware('auth')
        ->name('history');
    Route::get('/kategori/{category:slug}', [EksplorPediaController::class, 'category'])->name('category');
    Route::get('/quiz/{quiz:slug}', [EksplorPediaController::class, 'quiz'])->name('quiz');
    Route::post('/quiz/{quiz:slug}/submit', [EksplorPediaController::class, 'submit'])->name('submit');
});

// 8. Modul URBAN RALLY (Multi-Modal Transit Trails & Rekomendasi Rute)
Route::prefix('urban-rally')->name('urban-rally.')->group(function () {
    Route::get('/', [UrbanRallyController::class, 'index'])->name('index');
    Route::get('/{trail:slug}', [UrbanRallyController::class, 'show'])->name('show');
});
