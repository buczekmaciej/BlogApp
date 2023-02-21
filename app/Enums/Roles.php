<?php

namespace App\Enums;

use App\Enums\Traits\NamesTrait;
use App\Enums\Traits\ValuesTrait;

enum Roles: string
{
    use NamesTrait, ValuesTrait;

    case USER = "USER";
    case WRITER = "WRITER";
    case ADMIN = "ADMIN";
}
