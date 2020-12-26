<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use Exception;
use Robier\Enum\Exception as ExceptionInterface;

final class NoConstantsDefined extends Exception implements ExceptionInterface
{
    public function __construct(string $class)
    {
        $message = sprintf('No constants defined in enum class %s', $class);

        parent::__construct($message);
    }
}
