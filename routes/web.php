<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CashBoxController;
use App\Http\Controllers\CashCreditController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChallanController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\DailySaleController;
use App\Http\Controllers\DamagedStockController;
use App\Http\Controllers\DamageSendingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LiftingController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesRepController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('markets', MarketController::class)->except('show');
    Route::resource('sales-reps', SalesRepController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/daily-sales', [ReportController::class, 'dailySales'])->name('reports.daily-sales');
    Route::get('/reports/sr-wise', [ReportController::class, 'srWise'])->name('reports.sr-wise');
    Route::get('/reports/product-wise', [ReportController::class, 'productWise'])->name('reports.product-wise');
    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/credit', [ReportController::class, 'credit'])->name('reports.credit');

    Route::get('/company-profile', [CompanyProfileController::class, 'edit'])->name('company-profile.edit');
    Route::put('/company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');

    Route::resource('suppliers', SupplierController::class)->except('show');
    Route::resource('supplier-payments', SupplierPaymentController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('expenses', ExpenseController::class)->only(['index', 'create', 'store', 'destroy']);

    Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');

    Route::get('/daily-sales', [DailySaleController::class, 'index'])->name('daily-sales.index');
    Route::get('/daily-sales/entry', [DailySaleController::class, 'entry'])->name('daily-sales.entry');
    Route::post('/daily-sales/entry', [DailySaleController::class, 'save'])->name('daily-sales.save');
    Route::get('/daily-sales/challan', [ChallanController::class, 'show'])->name('daily-sales.challan');

    Route::get('/liftings', [LiftingController::class, 'index'])->name('liftings.index');
    Route::get('/liftings/entry', [LiftingController::class, 'entry'])->name('liftings.entry');
    Route::post('/liftings/entry', [LiftingController::class, 'save'])->name('liftings.save');

    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

    Route::resource('damaged-stock', DamagedStockController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('damage-sendings', DamageSendingController::class)->only(['index', 'create', 'store']);

    Route::get('/cash-credit', [CashCreditController::class, 'index'])->name('cash-credit.index');
    Route::get('/cash-credit/entry', [CashCreditController::class, 'entry'])->name('cash-credit.entry');
    Route::post('/cash-credit/entry', [CashCreditController::class, 'save'])->name('cash-credit.save');

    Route::get('/cash-box', [CashBoxController::class, 'index'])->name('cash-box.index');
    Route::get('/cash-box/entry', [CashBoxController::class, 'entry'])->name('cash-box.entry');
    Route::post('/cash-box/entry', [CashBoxController::class, 'save'])->name('cash-box.save');
});
