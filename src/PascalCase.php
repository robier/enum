<?php

declare(strict_types = 1);

namespace Robier\Enum;

/**
 * Converts string from different case standards (listed below) to PascalCase
 * - camelCase
 * - PascalCase
 * - UPPER_SNAKE_CASE
 * - lower_snake_case
 *
 * @internal
 */
final class PascalCase
{
    /**
     * @var string
     */
    private $pascalCase;

    public function __construct(string $pascalCase)
    {
        $this->pascalCase = $pascalCase;
    }

    public static function resolve(string $name): self
    {
        $upperSnakeCase = UpperSnakeCase::resolve($name);

        return new self(
            str_replace(
                '_',
                '',
                ucwords(
                    strtolower(
                        (string)$upperSnakeCase),
                    '_'
                )
            )
        );
    }

    public function __toString(): string
    {
        return $this->pascalCase;
    }
}
