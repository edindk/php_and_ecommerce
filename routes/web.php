<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    // Product routes
    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'showProducts'])->name('products');
    Route::post('/products/update', [\App\Http\Controllers\ProductController::class, 'edit'])->name('productsUpdate');
    Route::post('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('create');
    Route::get('/products/delete/{productID}', [\App\Http\Controllers\ProductController::class, 'delete'])->name('delete');
    Route::get('/products/search/{name?}', [\App\Http\Controllers\ProductController::class, 'search'])->name('search');

    // Category routes
    Route::post('/categories/create', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categoriesCreate');
    Route::post('/categories/update', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('categoriesUpdate');
    Route::get('/categories/delete/{id}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('categoriesDelete');
    Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'showCategories'])->name('categories');

    // Order routes
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'showOrders'])->name('orders');
    Route::get('/orders/sortbynewestdate', [\App\Http\Controllers\OrderController::class, 'sortByNewestDate'])->name('sortByNewestDate');
    Route::get('/orders/sortbyoldestdate', [\App\Http\Controllers\OrderController::class, 'sortByOldestDate'])->name('sortByOldestDate');
    Route::get('/orders/sortbytotal', [\App\Http\Controllers\OrderController::class, 'sortByTotal'])->name('sortByTotal');

    // Dashboard routes
    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('home');
    Route::get('/broadcast', [\App\Http\Controllers\DashboardController::class, 'broadcast'])->name('broadcast');

    // User and profile routes
    Route::resource('user', 'App\Http\Controllers\UserController', ['except' => ['show']]);
    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'App\Http\Controllers\ProfileController@edit']);
    Route::put('profile', ['as' => 'profile.update', 'uses' => 'App\Http\Controllers\ProfileController@update']);

    // Route to be deleted
    Route::get('/table-list', function () {
        return view('pages.tables');
    })->name('table');
    Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'App\Http\Controllers\ProfileController@password']);
});

