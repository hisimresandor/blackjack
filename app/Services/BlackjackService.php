<?php

namespace App\Services;

class BlackjackService
{
    public function getValue(array $cards): string
    {
        $value = 0;
        $aces = 0;

        // return $cards[0]["rank"];

        foreach ($cards as $card){
            if ($card["rank"] != "A" && $card["rank"] != "J" && $card["rank"] != "Q" && $card["rank"] != "K") {
                $value += intval($card["rank"]);
            } else if ($card["rank"] == "J" || $card["rank"] == "Q" || $card["rank"] == "K") {
                $value += 10;
            } else {
                $aces++;
            }
        }

        for ($i = 0; $i < $aces; $i++) {
            if ($value + 11 <= 21) {
                $value += 11;
            } else {
                $value++;
            }
        }

        return $value;
    }
}
