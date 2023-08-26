<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum ProjectStatuses: int
{
    use HasValues;

    case CLOSED = 0;
    case PRE_LAUNCHED = 1;
    case CANCELLED = 2;
    case LIVE = 3;
    case HOLD = 4;

    public function color(): string
    {
        return match ($this) {
            ProjectStatuses::CLOSED => 'danger',
            ProjectStatuses::PRE_LAUNCHED => 'secondary',
            ProjectStatuses::CANCELLED => 'amber',
            ProjectStatuses::LIVE => 'success',
            ProjectStatuses::HOLD => 'warning',
        };
    }
}
