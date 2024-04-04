<?php

use App\Http\Controllers\BalanceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'index'])->middleware(['auth', 'verified'])->name('index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/balance/withdraw', [BalanceController::class, 'withdraw'])->name('balance.withdraw');
    Route::patch('/balance/deposit', [BalanceController::class, 'deposit'])->name('balance.deposit');
    Route::post('/balance', [BalanceController::class, 'update'])->name('balance.update');
});

require __DIR__.'/auth.php';
