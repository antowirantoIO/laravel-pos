<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UOMController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::get('/purchase_orders', [OrderController::class, 'purchase'])->name('orders.purchase');
    Route::post('/update-purchase-price', [ProductController::class, 'updatePurchasePrice'])->name('products.updatePurchasePrice');
    Route::post('/update-expired-date', [ProductController::class, 'updatePurchaseExpiredDate'])->name('products.updatePurchaseExpiredDate');
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);
	Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
	Route::resource('order_items', OrderItemController::class);
    Route::resource('uom', UOMController::class);

    Route::get('/purchase_orders/show/{id}', [OrderController::class, 'show_purchase'])->name('orders.purchase_order');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/cart/empty', [CartController::class, 'empty']);
	
	Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.index');
	Route::post('/purchase', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::post('/purchase/change-qty', [PurchaseController::class, 'changeQty']);
    Route::delete('/purchase/delete', [PurchaseController::class, 'delete']);
    Route::delete('/purchase/empty', [PurchaseController::class, 'empty']);

    Route::get('/reports', [App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/download', [App\Http\Controllers\ReportsController::class, 'download'])->name('reports.download');
    Route::get('/reports/labakotor', [App\Http\Controllers\ReportsController::class, 'calculate'])->name('reports.hitungLabaKotor');
});

Route::get('suppliers/json', function() {
    $suppliers = \App\Models\Supplier::all();
    return response()->json($suppliers, 200);
});
