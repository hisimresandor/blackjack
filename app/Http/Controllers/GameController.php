<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\StoreRequest;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;

class GameController extends Controller
{
    public function store(StoreRequest $request) : RedirectResponse {
        $game = $request->validated();
        $player_cards = [];
        $dealer_cards = [];
        $deck = [];
        foreach ($game['player_cards'] as $card) {
            $player_cards[] = $card['id'];
        }
        foreach ($game['dealer_cards'] as $card) {
            $dealer_cards[] = $card['id'];
        }
        foreach ($game['deck'] as $card) {
            $deck[] = $card['id'];
        }
        $game['player_cards'] = $player_cards;
        $game['dealer_cards'] = $dealer_cards;
        $game['deck'] = $deck;
        $game['user_id'] = auth()->id();
        Game::create($game);
        return redirect()->back();
    }
}
