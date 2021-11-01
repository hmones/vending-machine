<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutAllController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('users', UserController::class)->only('store');
Route::resource('products', ProductController::class)->only(['index', 'show']);
Route::post('login', [LoginController::class, 'store'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('users', UserController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('products', ProductController::class)->only(['store', 'update', 'destroy']);
    Route::post('deposit', [DepositController::class, 'store'])->name('deposit');
    Route::delete('reset', [DepositController::class, 'destroy'])->name('reset');
    Route::post('buy', [OrderController::class, 'store'])->name('buy');
    Route::post('logout', [LogoutController::class, 'store'])->name('logout');
    Route::post('logout/all', [LogoutAllController::class, 'store'])->name('logout.all');
});


