<?php

declare(strict_types = 1);

namespace Robier\Enum;

use BadMethodCallException;

/**
 * Converts string from different case standards (listed below) to UPPER_SNAKE_CASE
 * - camelCase
 * - PascalCase
 * - UPPER_SNAKE_CASE
 * - lower_snake_case
 *
 * @internal
 */
final class UpperSnakeCase
{
    /**
     * @var string
     */
    private string $upperSnakeCase;

    public function __construct(string $upperSnakeCase)
    {
        $this->upperSnakeCase = $upperSnakeCase;
    }

    public static function resolve(string $name): self
    {
        if (!preg_match('/^[a-z_\s]+$/ui', $name)) {
            throw new BadMethodCallException("$name is not a valid variable name");
        }

        $name = trim($name, '_');

        if (preg_match('/^[A-Z_]+$/u', $name)) {
            return new static($name);
        }

        if (strtolower($name) === $name) {
            $name = strtoupper($name);
        }

        if (strtoupper($name) !== $name) {
            if ($pregReplaceValue = preg_replace('/(.)(?=[A-Z])/u', '$1_', $name)) {
                // preg_replace can return null, we do not want that
                $name = $pregReplaceValue;
            }
        }

        $name = strtoupper($name);

        if (mb_strpos($name, '__') !== false) {
            $name = (string)preg_replace('/_{2,}/ui', '_', $name);
        }

        return new static($name);
    }

    public function __toString(): string
    {
        return $this->upperSnakeCase;
    }
}
