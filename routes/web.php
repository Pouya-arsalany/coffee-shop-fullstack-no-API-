<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'index')->name('index');
Route::view('/service', 'service')->name('service');
Route::get('/reservation', [OrderController::class, 'showOrder'])
    ->name('reserve')
    ->middleware('auth');
/*
|--------------------------------------------------------------------------
| Admin Dashboard Route
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

        Route::get('/', [AuthController::class, 'adminDashboard'])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Category Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/categories')->name('categories.')->controller(CategoryController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{category}', 'edit')->name('edit');
    Route::post('/update/{category}', 'update')->name('update');
    Route::delete('/delete/{category}', 'delete')->name('delete');
    Route::get('/search', 'search')->name('search');
});

/*
|--------------------------------------------------------------------------
| Product Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/products')->name('products.')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/create', 'create')->name('create');
    Route::post('/store', 'store')->name('store');
    Route::get('/edit/{product}', 'edit')->name('edit');
    Route::put('/update/{product}', 'update')->name('update');
    Route::delete('/delete/{product}', 'delete')->name('delete');
    Route::get('/search', 'search')->name('search');
});

/*
|--------------------------------------------------------------------------
| Tables Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/tables')->name('tables.')->controller(TableController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::delete('/delete/{table}', 'destroy')->name('delete');
});

/*
|--------------------------------------------------------------------------
| Compact Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('/menu')->name('menu.')->controller(MenuController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/category/{id}', 'filterByCategory')->name('filter');
});

/*
|--------------------------------------------------------------------------
| Auth Management Routes
|--------------------------------------------------------------------------
*/
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Order/Reservation Management Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/reservation')->name('reservation.')->middleware('auth')->group(function () {
    Route::post('/add/{product}', [OrderController::class, 'addProduct'])->name('add');
    Route::delete('/item/{id}', [OrderController::class, 'removeItem'])->name('removeItem');
    Route::get('/select-table', [OrderController::class, 'showTableSelection'])->name('selectTable');
    Route::post('/select-table', [OrderController::class, 'chooseTable'])->name('chooseTable');

    Route::post('/submit', [OrderController::class, 'submitOrder'])->name('submit');

    Route::delete('/clear', [OrderController::class, 'clearOrder'])->name('clear');
});

Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/store', 'store')->name('store');
    Route::delete('/destroy/{order}', 'destroy')->name('destroy');
});
