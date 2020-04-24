<?php

declare(strict_types = 1);

namespace Robier\Enum;

/**
 * Converts string between different case standards:
 * - camelCase
 * - PascalCase
 * - UPPER_SNAKE_CASE
 * - lower_snake_case
 * - UPPER-KEBAB-CASE
 * - lower-kebab-case
 * - UPPER SPACE CASE
 * - lower space case
 *
 * @internal
 */
final class Name
{
    /**
     * @var string
     */
    private $upperSnakeCase;

    /**
     * @var string
     */
    private $original;

    public function __construct(string $upperSnakeCase, string $original)
    {
        $this->upperSnakeCase = $upperSnakeCase;
        $this->original = $original;
    }

    public static function resolve(string $name): self
    {
        $original = $name;
        if (!preg_match('/^[a-z_\-\s]+$/ui', $name)) {
            throw new \Exception('Can not be variable name');
        }

        $name = trim($name, ' -_');

        if (preg_match('/^[A-Z_]+$/u', $name)) {
            return new static($name, $original);
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

        $name = str_replace([' ', '-'], '_', $name);

        if (mb_strpos($name, '__') !== false) {
            $name = preg_replace('/_{2,}/ui', '_', $name);
        }

        return new static($name, $original);
    }

    public function __toString(): string
    {
        return $this->upperSnakeCase;
    }

    public function pascalCase(): string
    {
        return str_replace('_', '', ucwords(strtolower($this->upperSnakeCase), '_'));
    }

    public function camelCase(): string
    {
        return lcfirst($this->pascalCase());
    }

    public function upperSnakeCase(): string
    {
        return $this->upperSnakeCase;
    }

    public function lowerSnakeCase(): string
    {
        return strtolower($this->upperSnakeCase);
    }

    public function upperKebabCase(): string
    {
        return str_replace('_', '-', $this->upperSnakeCase);
    }

    public function lowerKebabCase(): string
    {
        return strtolower($this->upperKebabCase());
    }

    public function upperSpaceCase(): string
    {
        return str_replace('_', ' ', $this->upperSnakeCase);
    }

    public function lowerSpaceCase(): string
    {
        return strtolower($this->upperSpaceCase());
    }

    public function original(): string
    {
        return $this->original;
    }

    public function isEqual(self $name): bool
    {
        return $this->upperSnakeCase() === $name->upperSnakeCase();
    }

    public function isSame(string $name): bool
    {
        return $this->upperSnakeCase() === static::resolve($name)->upperSnakeCase();
    }
}
