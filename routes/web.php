<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyProfileController;
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

    // Placeholder routes for upcoming modules (built in next steps)
    Route::get('/daily-sales', fn () => view('placeholder', ['title' => 'Daily Sales Entry']))->name('daily-sales.index');
    Route::get('/liftings', fn () => view('placeholder', ['title' => 'Lifting / Purchase']))->name('liftings.index');
    Route::get('/stock', fn () => view('placeholder', ['title' => 'Stock Ledger']))->name('stock.index');
    Route::get('/cash-credit', fn () => view('placeholder', ['title' => 'Cash & Credit']))->name('cash-credit.index');
});
