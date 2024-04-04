<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';
    case BET = 'bet';
    case WIN = 'win';

    public static function getValues(): array
    {
        return [
            self::DEPOSIT,
            self::WITHDRAW,
            self::BET,
            self::WIN,
        ];
    }
}
