<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use Robier\Enum\HasDefault as Enum;

enum HasDefault: int
{
    use Enum;
    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case USER = 3;
    case CLIENT = 4;
}
