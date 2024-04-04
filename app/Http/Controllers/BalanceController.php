<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Http\Requests\Balance\UpdateRequest;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class BalanceController extends Controller
{
    /**
     * Show the form for editing the user's balance.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Balance/Edit');
    }

    public function bet(Request $request): RedirectResponse
    {
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'type' => TransactionType::BET,
            'amount' => $request->amount,
            'balance_before' => $request->user()->balance,
            'balance_after' => $request->user()->balance - $request->amount,
        ]);

        $request->user()->balance -= $request->amount;

        $request->user()->save();

        return redirect()->back();
    }

    public function win(Request $request): RedirectResponse
    {
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'type' => TransactionType::WIN,
            'amount' => $request->amount,
            'balance_before' => $request->user()->balance,
            'balance_after' => $request->user()->balance + $request->amount,
        ]);

        $request->user()->balance += $request->amount;

        $request->user()->save();

        return redirect()->back();
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'type' => TransactionType::WITHDRAW,
            'amount' => $request->amount,
            'balance_before' => $request->user()->balance,
            'balance_after' => $request->user()->balance - $request->amount,
        ]);

        $request->user()->balance -= $request->amount;

        $request->user()->save();

        return redirect()->back();
    }

    public function deposit(Request $request): RedirectResponse
    {
        $transaction = Transaction::create([
            'user_id' => $request->user()->id,
            'type' => TransactionType::DEPOSIT,
            'amount' => $request->amount,
            'balance_before' => $request->user()->balance,
            'balance_after' => $request->user()->balance + $request->amount,
        ]);

        $request->user()->balance += $request->amount;

        $request->user()->save();

        return redirect()->back();
    }
}
