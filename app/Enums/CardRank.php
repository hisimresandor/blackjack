<?php

namespace App\Enums;

enum CardRank: string
{
    case ACE = 'A';
    case ONE = '1';
    case TWO = '2';
    case THREE = '3';
    case FOUR = '4';
    case FIVE = '5';
    case SIX = '6';
    case SEVEN = '7';
    case EIGHT = '8';
    case NINE = '9';
    case TEN = '10';
    CASE JACK = 'J';
    CASE QUEEN = 'Q';
    CASE KING = 'K';

    public function toValue(): string
    {
        return match ($this) {
            self::ACE => 'A',
            self::ONE => '1',
            self::TWO => '2',
            self::THREE => '3',
            self::FOUR => '4',
            self::FIVE => '5',
            self::SIX => '6',
            self::SEVEN => '7',
            self::EIGHT => '8',
            self::NINE => '9',
            self::TEN => '10',
            self::JACK => '10',
            self::QUEEN => '10',
            self::KING => '10',
        };
    }
}
