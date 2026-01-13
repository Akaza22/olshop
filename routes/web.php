<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VtoController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('katalog');
});


Route::get('/katalog', [ProductController::class, 'index'])->name('katalog');
Route::get('/about', function () { return view('about'); })->name('about');

// Produk & AI Virtual Try-On Routes
Route::prefix('produk')->group(function () {
    Route::get('/{id}', [ProductController::class, 'show'])->name('produk.show');
    // Pastikan dua baris ini ada:
    // Route::post('/vto/process', [VtoController::class, 'process'])->name('vto.process');
    // Route::get('/vto/status', [VtoController::class, 'checkStatus'])->name('vto.status');
});

/*
|--------------------------------------------------------------------------
| Cart Routes (Public/Session Based)
|--------------------------------------------------------------------------
*/
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Harus Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    
    // Customer Checkout & History
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [CartController::class, 'storeCheckout'])->name('checkout.store');
    Route::get('/orders', [CartController::class, 'orderHistory'])->name('orders.index');

    /*
    |----------------------------------------------------------------------
    | Admin Routes (Admin Only)
    |----------------------------------------------------------------------
    */
    Route::middleware(['admin'])
        ->prefix('admin')
        ->name('admin.') 
        ->group(function () {
            
            // Dashboard
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
            
            // Manajemen Produk (Resource: index, create, store, show, edit, update, destroy)
            Route::resource('products', AdminController::class); 
            
            // Manajemen Pesanan
            Route::prefix('orders')->name('orders.')->group(function () {
                Route::get('/', [AdminController::class, 'orders'])->name('index');
                Route::post('/{id}/ship', [AdminController::class, 'shipOrder'])->name('ship');
            });
        });
});

// Breeze Auth Routes (login, register, logout, dll)
require __DIR__.'/auth.php';