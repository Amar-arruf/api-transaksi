<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {
    Route::get('/sales', [App\Http\Controllers\transaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/sales/current-month', [App\Http\Controllers\transaksiController::class, 'getTransactionCurrentMonthWithTargetDatas'])
        ->name('transaksi.current-month');
    Route::get('/sales/current-month-with-target', [App\Http\Controllers\transaksiController::class, 'getTransactionCurrentMonthWithTarget']);


    Route::post('/customer', [App\Http\Controllers\CustomerController::class, 'create'])
        ->name('customer.create')->withoutMiddleware(VerifyCsrfToken::class);
    Route::put('/customer/{id}', [App\Http\Controllers\CustomerController::class, 'update'])
        ->name('customer.update')->withoutMiddleware(VerifyCsrfToken::class);
});
