<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use Exception;

final class NoConstantsDefined extends Exception
{
    public function __construct(string $class)
    {
        $message = sprintf('No constants defined in enum class %s', $class);

        parent::__construct($message);
    }
}
