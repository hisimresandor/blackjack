<?php

namespace App\Enums;

enum CardSuit: string
{
    case HEARTS = 'H';
    case DIAMONDS = 'D';
    case CLUBS = 'C';
    case SPADES = 'S';

    public static function getValues(): array
    {
        return [
            self::HEARTS,
            self::DIAMONDS,
            self::CLUBS,
            self::SPADES,
        ];
    }
}
