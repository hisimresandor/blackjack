<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->middleware(['auth', 'verified'])->name('index');

Route::middleware('auth')->group(function () {

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

    Route::post('/game', [GameController::class, 'store'])->name('game.store');

});

require __DIR__.'/auth.php';
