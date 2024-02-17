<?php

namespace App\Contracts;

enum StatusEnum: int
{
    case IN_PROGRESS = 0;
    case PAUSED = 1;
    case OPEN = 2;
    case CANCELLED = 3;
    case COMPLETED = 4;

    public function label(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'in progress',
            self::PAUSED => 'paused',
            self::OPEN => 'open',
            self::CANCELLED => 'cancelled',
            self::COMPLETED => 'completed',
        };
    }
}
