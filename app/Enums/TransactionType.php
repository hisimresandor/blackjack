<?php

namespace App\Enums;

enum TransactionType: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAW = 'withdraw';

    public static function getValues(): array
    {
        return [
            self::DEPOSIT,
            self::WITHDRAW,
        ];
    }
}
