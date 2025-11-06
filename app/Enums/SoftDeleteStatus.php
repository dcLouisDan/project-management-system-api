<?php

namespace App\Enums;

enum SoftDeleteStatus: string
{
    case ALL = 'all';
    case ACTIVE = 'active';
    case DELETED = 'deleted';

    public static function allStatus(): array
    {
        return array_map(fn ($status) => $status->value, self::cases());
    }

    public static function isValidStatus(string $Status): bool
    {
        return in_array($Status, self::allStatus());
    }
}
