<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use Exception;

final class Validation extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function unexpectedConstantType(string $class, string $name, string $type, string $expectedType): self
    {
        $message = sprintf(
            'Constant %s have a unexpected value type %s, %s expected in enum %s',
            $name,
            $type,
            $expectedType,
            $class
        );

        return new static($message);
    }

    public static function duplicatedConstantValues(string $class): self
    {
        $message = sprintf(
            'Duplicated constant values detected in enum %s',
            $class
        );

        return new static($message);
    }
}
