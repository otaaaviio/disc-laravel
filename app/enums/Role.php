<?php

namespace App\enums;

enum Role: string
{
    case Admin = 'Admin';
    case Member = 'Moderator';
    case Moderator = 'Member';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
