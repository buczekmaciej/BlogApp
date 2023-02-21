<?php

namespace App\Enums;

use App\Enums\Traits\NamesTrait;
use App\Enums\Traits\ValuesTrait;

enum WarrantStatus: string
{
    use NamesTrait, ValuesTrait;

    case SUSPENDED = "suspended";
    case BLOCKED = "blocked";
    case DELETED = "deleted";
}
