<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\DailySaleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesRepController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('markets', MarketController::class)->except('show');
    Route::resource('sales-reps', SalesRepController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');

    Route::get('/company-profile', [CompanyProfileController::class, 'edit'])->name('company-profile.edit');
    Route::put('/company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');

    Route::get('/daily-sales', [DailySaleController::class, 'index'])->name('daily-sales.index');
    Route::get('/daily-sales/entry', [DailySaleController::class, 'entry'])->name('daily-sales.entry');
    Route::post('/daily-sales/entry', [DailySaleController::class, 'save'])->name('daily-sales.save');

    // Placeholder routes for upcoming modules (built in next steps)
    Route::get('/liftings', fn () => view('placeholder', ['title' => 'Lifting / Purchase']))->name('liftings.index');
    Route::get('/stock', fn () => view('placeholder', ['title' => 'Stock Ledger']))->name('stock.index');
    Route::get('/cash-credit', fn () => view('placeholder', ['title' => 'Cash & Credit']))->name('cash-credit.index');
});
