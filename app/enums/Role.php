<?php

namespace App\enums;

enum Role: int
{
    case Admin = 1;
    case Member = 2;
    case Moderator = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Member => 'Member',
            self::Moderator => 'Moderator',
        };
    }

    public function getLabels(): array
    {
        return [
            self::Admin->value => 'Admin',
            self::Member->value => 'Member',
            self::Moderator->value => 'Moderator',
        ];
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
