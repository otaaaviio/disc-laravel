<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Member = 'member';
    case Moderator = 'moderator';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
