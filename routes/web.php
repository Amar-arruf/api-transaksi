<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {
    Route::get('/sales', [App\Http\Controllers\transaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/sales/current-month', [App\Http\Controllers\transaksiController::class, 'getTransactionCurrentMonthWithTargetDatas'])
        ->name('transaksi.current-month');
    Route::get('/sales/current-month-with-target', [App\Http\Controllers\transaksiController::class, 'getTransactionCurrentMonthWithTarget']);
});
