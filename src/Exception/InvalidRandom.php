<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use InvalidArgumentException;
use Robier\Enum\Exception;

final class InvalidRandom extends InvalidArgumentException implements Exception
{
    public function __construct()
    {
        $message = 'Can not return random value if all possibilities are excluded';

        parent::__construct($message, 0, null);
    }
}
