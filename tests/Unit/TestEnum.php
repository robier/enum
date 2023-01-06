<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use Robier\Enum\BooleanOperators;

/**
 * @method bool isSuperAdmin()
 * @method bool notSuperAdmin()
 * @method bool isAdmin()
 * @method bool notAdmin()
 * @method bool isUser()
 * @method bool notUser()
 * @method bool isClient()
 * @method bool notClient()
 */
enum TestEnum: int
{
    use BooleanOperators;

    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case USER = 3;
    case CLIENT = 4;
}
