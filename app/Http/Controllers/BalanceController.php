<?php

namespace App\Http\Controllers;

use App\Http\Requests\Balance\UpdateRequest;
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

    /**
     * Update the user's balance.
     */
    public function update(UpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    public function withdraw(Request $request): RedirectResponse
    {
        $request->user()->balance -= $request->amount;

        $request->user()->save();

        return redirect()->back();
    }

    public function deposit(Request $request): RedirectResponse
    {
        $request->user()->balance += $request->amount;

        $request->user()->save();

        return redirect()->back();
    }
}
