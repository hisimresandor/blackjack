<?php

namespace Database\Seeders;

use App\Enums\CardRank;
use App\Enums\CardSuit;
use App\Models\Card;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (CardSuit::cases() as $suit) {
            foreach (CardRank::cases() as $rank) {
                Card::create([
                    'suit' => $suit->value,
                    'rank' => $rank->value,
                    'image_url' => "/assets/img/cards/{$rank->value}-{$suit->value}.png"
                ]);
            }
        }
    }
}
