<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function index()
    {
        $balance = auth()->user()->balance;
        $deck = Card::all();
        return Inertia::render('Blackjack', [
            'balance' => $balance,
            'deck' => $deck,
        ]);
    }
}
