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
use App\Http\Controllers\FreeOfferController;
use App\Http\Controllers\IncentiveController;
use App\Http\Controllers\LiftingController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\PackTypeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductBulkController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RetailerController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SalesRepController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffLeaveController;
use App\Http\Controllers\StaffSalaryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AllowActivationAccess;
use App\Models\CompanyProfile;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

// ------------------------------------ Site Maintenance (login-free with a key) ------------------------------------
Route::middleware(AllowActivationAccess::class)->prefix('dashboard/activation')->group(function () {
    Route::get('/', [MaintenanceController::class, 'show'])->name('maintenance.show');
    Route::post('/toggle', [MaintenanceController::class, 'toggle'])->name('maintenance.toggle');
    Route::get('/preview', [MaintenanceController::class, 'preview'])->name('maintenance.preview');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('permission:pack-types')->group(function () {
        Route::get('/pack-types', [PackTypeController::class, 'index'])->name('pack-types.index');
        Route::post('/pack-types/pack', [PackTypeController::class, 'storePack'])->name('pack-types.store-pack');
        Route::delete('/pack-types/pack/{packType}', [PackTypeController::class, 'destroyPack'])->name('pack-types.destroy-pack');
        Route::post('/pack-types/quantity', [PackTypeController::class, 'storeQuantity'])->name('pack-types.store-quantity');
        Route::delete('/pack-types/quantity/{quantityType}', [PackTypeController::class, 'destroyQuantity'])->name('pack-types.destroy-quantity');
    });

    Route::middleware('permission:categories')->group(function () {
        Route::resource('categories', CategoryController::class)->except('show');
    });
    Route::middleware('permission:markets')->group(function () {
        Route::resource('markets', MarketController::class)->except('show');
    });
    Route::middleware('permission:retailers')->group(function () {
        Route::resource('retailers', RetailerController::class)->except('show');
    });
    Route::middleware('permission:sales-reps')->group(function () {
        Route::resource('sales-reps', SalesRepController::class)->except('show');
    });

    Route::middleware('permission:products')->group(function () {
        Route::get('/products/multi-add', [ProductBulkController::class, 'multiCreate'])->name('products.multi-add');
        Route::post('/products/multi-add', [ProductBulkController::class, 'multiStore'])->name('products.multi-store');
        Route::get('/products/low-stock', [ProductBulkController::class, 'lowStock'])->name('products.low-stock');
        Route::get('/products/bulk-edit', [ProductBulkController::class, 'bulkEdit'])->name('products.bulk-edit');
        Route::post('/products/bulk-edit', [ProductBulkController::class, 'bulkUpdate'])->name('products.bulk-update');
        Route::resource('products', ProductController::class)->except('show');
    });

    Route::middleware('permission:free-offers')->group(function () {
        Route::resource('free-offers', FreeOfferController::class)->except('show');
    });

    Route::middleware('permission:reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily-sales', [ReportController::class, 'dailySales'])->name('reports.daily-sales');
        Route::get('/reports/sr-wise', [ReportController::class, 'srWise'])->name('reports.sr-wise');
        Route::get('/reports/product-wise', [ReportController::class, 'productWise'])->name('reports.product-wise');
        Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('/reports/credit', [ReportController::class, 'credit'])->name('reports.credit');
        Route::get('/reports/sales-summary', [ReportController::class, 'salesSummary'])->name('reports.sales-summary');
        Route::get('/reports/closing-summary', [ReportController::class, 'closingSummary'])->name('reports.closing-summary');
    });

    Route::middleware('permission:company-profile')->group(function () {
        Route::get('/company-profile', [CompanyProfileController::class, 'edit'])->name('company-profile.edit');
        Route::put('/company-profile', [CompanyProfileController::class, 'update'])->name('company-profile.update');
    });

    Route::middleware('permission:suppliers')->group(function () {
        Route::resource('suppliers', SupplierController::class)->except('show');
    });
    Route::middleware('permission:supplier-payments')->group(function () {
        Route::resource('supplier-payments', SupplierPaymentController::class)->only(['index', 'create', 'store', 'destroy']);
    });
    Route::middleware('permission:transactions')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });
    Route::middleware('permission:incentives')->group(function () {
        Route::resource('incentives', IncentiveController::class)->only(['index', 'create', 'store', 'destroy']);
    });
    Route::middleware('permission:payments')->group(function () {
        Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'destroy']);
    });
    Route::middleware('permission:expenses')->group(function () {
        Route::resource('expenses', ExpenseController::class)->only(['index', 'create', 'store', 'destroy']);
    });

    Route::middleware('permission:audit-logs')->group(function () {
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('/role-permissions', [RolePermissionController::class, 'edit'])->name('role-permissions.edit');
        Route::put('/role-permissions', [RolePermissionController::class, 'update'])->name('role-permissions.update');
    });

    Route::middleware('permission:settings')->group(function () {
        Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::middleware('permission:support')->group(function () {
        Route::get('/support', fn () => view('support.index', ['company' => CompanyProfile::first()]))->name('support.index');
    });

    Route::middleware('permission:staff')->group(function () {
        Route::resource('staff', StaffController::class)->except('show');
    });
    Route::middleware('permission:staff-attendance')->group(function () {
        Route::get('/staff-attendance', [StaffAttendanceController::class, 'index'])->name('staff-attendance.index');
        Route::post('/staff-attendance', [StaffAttendanceController::class, 'save'])->name('staff-attendance.save');
    });
    Route::middleware('permission:staff-leave')->group(function () {
        Route::resource('staff-leave', StaffLeaveController::class)->only(['index', 'create', 'store', 'destroy']);
        Route::put('/staff-leave/{staffLeave}/status', [StaffLeaveController::class, 'updateStatus'])->name('staff-leave.status');
    });
    Route::middleware('permission:staff-salary')->group(function () {
        Route::get('/staff-salary', [StaffSalaryController::class, 'index'])->name('staff-salary.index');
        Route::post('/staff-salary', [StaffSalaryController::class, 'store'])->name('staff-salary.store');
        Route::post('/staff-salary/{staffSalary}/mark-paid', [StaffSalaryController::class, 'markPaid'])->name('staff-salary.mark-paid');
    });

    Route::middleware('permission:daily-sales')->group(function () {
        Route::get('/daily-sales', [DailySaleController::class, 'index'])->name('daily-sales.index');
        Route::get('/daily-sales/entry', [DailySaleController::class, 'entry'])->name('daily-sales.entry');
        Route::post('/daily-sales/entry', [DailySaleController::class, 'save'])->name('daily-sales.save');
        Route::get('/daily-sales/challan', [ChallanController::class, 'show'])->name('daily-sales.challan');
    });

    Route::middleware('permission:liftings')->group(function () {
        Route::get('/liftings', [LiftingController::class, 'index'])->name('liftings.index');
        Route::get('/liftings/entry', [LiftingController::class, 'entry'])->name('liftings.entry');
        Route::post('/liftings/entry', [LiftingController::class, 'save'])->name('liftings.save');
    });

    Route::middleware('permission:purchase-orders')->group(function () {
        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders.index');
        Route::get('/purchase-orders/create', [PurchaseOrderController::class, 'create'])->name('purchase-orders.create');
        Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
        Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');
    });

    Route::middleware('permission:stock')->group(function () {
        Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    });

    Route::middleware('permission:damaged-stock')->group(function () {
        Route::resource('damaged-stock', DamagedStockController::class)->only(['index', 'create', 'store', 'destroy']);
    });
    Route::middleware('permission:damage-sendings')->group(function () {
        Route::resource('damage-sendings', DamageSendingController::class)->only(['index', 'create', 'store']);
    });

    Route::middleware('permission:cash-credit')->group(function () {
        Route::get('/cash-credit', [CashCreditController::class, 'index'])->name('cash-credit.index');
        Route::get('/cash-credit/entry', [CashCreditController::class, 'entry'])->name('cash-credit.entry');
        Route::post('/cash-credit/entry', [CashCreditController::class, 'save'])->name('cash-credit.save');
    });

    Route::middleware('permission:cash-box')->group(function () {
        Route::get('/cash-box', [CashBoxController::class, 'index'])->name('cash-box.index');
        Route::get('/cash-box/entry', [CashBoxController::class, 'entry'])->name('cash-box.entry');
        Route::post('/cash-box/entry', [CashBoxController::class, 'save'])->name('cash-box.save');
    });
});
