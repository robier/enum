<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use BadMethodCallException;
use Robier\Enum\Exception;

final class BadMethodCall extends BadMethodCallException implements Exception
{
    public static function static(string $class, string $name): self
    {
        $message = sprintf('Static method %s not found in enum %s', $name, $class);

        return new self($message);
    }

    public static function instance(string $class, string $name): self
    {
        $message = sprintf('Method %s does not exists in enum %s', $name, $class);

        return new self($message);
    }
}
