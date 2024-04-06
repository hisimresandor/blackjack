<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->middleware(['auth', 'verified'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('balance', [BalanceController::class, 'edit'])->name('balance.edit');
    Route::post('/balance/bet', [BalanceController::class, 'bet'])->name('balance.bet');
    Route::post('/balance/win', [BalanceController::class, 'win'])->name('balance.win');
    Route::post('/balance/deposit', [BalanceController::class, 'deposit'])->name('balance.deposit');
    Route::post('/balance/withdraw', [BalanceController::class, 'withdraw'])->name('balance.withdraw');
    Route::post('/game', [GameController::class, 'store'])->name('game.store');
});

require __DIR__.'/auth.php';
