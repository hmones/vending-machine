<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('users', UserController::class)->only('store');

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('users', UserController::class)->except(['store', 'edit', 'create']);
    Route::resource('products', ProductController::class)->except(['edit', 'create']);
    Route::post('deposit', [DepositController::class, 'store'])->name('deposit');
    Route::delete('reset', [DepositController::class, 'destroy'])->name('reset');
    Route::post('buy', [OrderController::class, 'store'])->name('buy');
});


