<?php

namespace App\Enums\Concerns;

use Illuminate\Support\Arr;

trait HasValues
{
    public static function values(): array
    {
        return array_column(array: static::cases(), column_key: 'values');
    }

    public static function pluck(string $value, ?string $key = null)
    {
        return Arr::pluck(static::cases(), $value, $key) ;
    }
}
