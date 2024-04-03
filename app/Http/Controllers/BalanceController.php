<?php

namespace App\Http\Controllers;

use App\Http\Requests\Balance\UpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BalanceController extends Controller
{

    /**
     * Update the user's balance.
     */
    public function update(UpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit');
    }
}
