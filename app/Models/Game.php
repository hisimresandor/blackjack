<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'user_id',
        'player_cards',
        'dealer_cards',
        'deck',
        'bet',
    ];

    protected $casts = [
        'player_cards' => 'array',
        'dealer_cards' => 'array',
        'deck' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
