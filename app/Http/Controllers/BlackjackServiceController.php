<?php

namespace App\Http\Controllers;

use App\Services\BlackjackService;
use Illuminate\Http\Request;

class BlackjackServiceController extends Controller
{
    protected $blackjackService;

    public function __construct(BlackjackService $blackjackService)
    {
        $this->blackjackService = $blackjackService;
    }

    public function getValue(Request $request)
    {
        $data = $this->blackjackService->getValue($request->cards);
        return response()->json($data);
    }
}
