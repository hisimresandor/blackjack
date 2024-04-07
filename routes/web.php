<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BlackjackController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::controller(BlackjackController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/game', 'store')->name('game.store');
    });

    Route::controller(ProfileController::class)->prefix('/profile')->name('profile.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::patch('/', 'update')->name('update');
        Route::delete('/', 'destroy')->name('destroy');
    });

    Route::controller(BalanceController::class)->prefix('/balance')->name('balance.')->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::post('/bet', 'bet')->name('bet');
        Route::post('/win', 'win')->name('win');
        Route::post('/deposit', 'deposit')->name('deposit');
        Route::post('/withdraw', 'withdraw')->name('withdraw');
    });
});

require __DIR__.'/auth.php';
