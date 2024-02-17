<?php

namespace App\Contracts;

use App\Models\User;

enum VisibilityEnum: int
{
    case GUEST = 0;
    case USER = 1;
    case ADMIN = 2;

    public function authorized(?User $user): bool
    {
        return match ($this) {
            self::ADMIN => !is_null($user) && $user->is_admin,
            self::USER => !is_null($user),
            default => true,
        };
    }
}
