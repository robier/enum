<?php

declare(strict_types = 1);

namespace Robier\Enum;

trait HasDefault
{
    static function try(string|int $value, self $default): self
    {
        $value = self::tryFrom($value);

        if($value === null) {
            return $default;
        }

        return $value;
    }

    static function tryName(string $name, self $default): self
    {
        $name = strtoupper($name);

        $cases = self::cases();

        foreach ($cases as $case) {
            if (strtoupper($case->name) === $name) {
                return $case;
            }
        }

        return $default;
    }
}
