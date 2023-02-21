<?php

namespace App\Enums\Traits;

trait NamesTrait
{
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }
}
