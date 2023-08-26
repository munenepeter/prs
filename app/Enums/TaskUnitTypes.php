<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum TaskUnitTypes: int
{
    use HasValues;

    case HOUR = 1;
    case PAGES = 2;
    case CHARACTERS = 3;

    public function color(): string
    {
        return match ($this) {
            TaskUnitTypes::HOUR => 'primary',
            TaskUnitTypes::PAGES => 'secondary',
            TaskUnitTypes::CHARACTERS => 'amber'
        };
    }
}
