<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'Admin';
    case Member = 'Member';
    case Moderator = 'Moderator';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
