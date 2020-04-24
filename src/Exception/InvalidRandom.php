<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use InvalidArgumentException;

final class InvalidRandom extends InvalidArgumentException
{
    public function __construct()
    {
        $message = 'Can not return random value if all possibilities are excluded';

        parent::__construct($message, 0, null);
    }
}
