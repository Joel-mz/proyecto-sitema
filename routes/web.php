<?php

use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Route;

// Public Catalog Routes
Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/categoria/{slug}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/producto/{slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/catalogo-pdf', [PdfController::class, 'download'])->name('catalog.pdf');

// Public Orders & Ticket PDF Routes
Route::post('/pedidos/registrar', [OrderController::class, 'store'])->name('orders.store');
Route::get('/pedidos/{order}/ticket-pdf', [OrderController::class, 'ticketPdf'])->name('orders.ticket');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes (Protected)
Route::prefix('admin')->middleware('auth')->group(function () {
    // Shared Dashboard & Catalog Management (Admin & Editor)
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Internal Quotes Module
    Route::get('/quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');
    Route::patch('/quotes/{quote}/status', [QuoteController::class, 'updateStatus'])->name('quotes.status');
    Route::resource('quotes', QuoteController::class);

    // Categories Bulk Actions
    Route::post('/categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
    Route::post('/categories/delete-all', [CategoryController::class, 'deleteAll'])->name('categories.deleteAll');
    Route::resource('categories', CategoryController::class)->except(['show']);

    // Products Bulk Actions
    Route::post('/products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');
    Route::post('/products/delete-all', [ProductController::class, 'deleteAll'])->name('products.deleteAll');
    Route::resource('products', ProductController::class)->except(['show']);

    // Banners / Slider Management
    Route::resource('banners', BannerController::class)->except(['show']);

    Route::get('/catalogo-pdf', [PdfController::class, 'download'])->name('admin.pdf');

    // Admin-Only Routes
    Route::middleware('role:admin')->group(function () {
        // Users Management
        Route::resource('users', UserController::class)->except(['show']);

        // Company Settings
        Route::get('/settings', [SettingController::class, 'edit'])->name('admin.settings.edit');
        Route::put('/settings', [SettingController::class, 'update'])->name('admin.settings.update');

        // Backup & Restore
        Route::get('/backup', [BackupController::class, 'index'])->name('admin.backup.index');
        Route::get('/backup/export', [BackupController::class, 'export'])->name('admin.backup.export');
        Route::post('/backup/restore', [BackupController::class, 'restore'])->name('admin.backup.restore');
        Route::post('/backup/reset', [BackupController::class, 'reset'])->name('admin.backup.reset');
    });
});
