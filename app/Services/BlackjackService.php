<?php

namespace App\Services;

class BlackjackService
{
    public function getValue(array $cards): string
    {
        $value = 0;
        $aces = 0;

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

    public function getResult(array $playerCards, array $dealerCards): int
    {
        $playerValue = $this->getValue($playerCards);
        $dealerValue = $this->getValue($dealerCards);

        if ($playerValue <= 21) {
            if ($playerValue == 21 && count($playerCards) == 2) {
                if ($dealerValue == 21) {
                    return 1;
                } else {
                    return 3;
                }
            } else if ($dealerValue > 21) {
                return 2;
            } else if ($playerValue == $dealerValue) {
                return 1;
            } else if ($playerValue > $dealerValue) {
                return 2;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
