<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use InvalidArgumentException;
use Robier\Enum\Exception;

final class InvalidEnum extends InvalidArgumentException implements Exception
{
    public static function name(string $class, string $name): self
    {
        $message = sprintf('Constant with name %s not found in enum %s', $name, $class);

        return new static($message);
    }

    /**
     * @param string|int $value
     */
    public static function value(string $class, $value): self
    {
        $message = sprintf('Constant with value %s not found in enum %s', $value, $class);

        return new static($message);
    }

    public static function index(string $class, int $index): self
    {
        $message = sprintf('Constant with index %s not found in enum %s', $index, $class);

        return new static($message);
    }
}
