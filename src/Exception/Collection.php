<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use InvalidArgumentException;
use Robier\Enum\Exception;

final class Collection extends InvalidArgumentException implements Exception
{
    public static function notObject($value): self
    {
        $type = gettype($value);

        $message = sprintf('Property provided is not an object, %s provided', $type);

        return new self($message);
    }

    public static function objectNotEnum(object $object): self
    {
        $message = sprintf('Property provided is not an enum object, "%s" provided', get_class($object));

        return new self($message);
    }

    public static function classNotEnum(string $class): self
    {
        $message = sprintf('Property provided is not an enum class name, class "%s" provided', $class);

        return new self($message);
    }
}
