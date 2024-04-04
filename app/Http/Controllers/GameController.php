<?php

namespace App\Http\Controllers;

use App\Http\Requests\Game\StoreRequest;
use App\Models\Game;
use Illuminate\Http\RedirectResponse;

class GameController extends Controller
{
    public function store(StoreRequest $request) : RedirectResponse {
        $game = $request->validated();
        $game['user_id'] = auth()->id();
        // dd($game);
        Game::create($game);
        return redirect()->back();
    }
}
