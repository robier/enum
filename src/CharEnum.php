<?php

declare(strict_types = 1);

namespace Robier\Enum;

trait CharEnum
{
    use StringEnum {
        byValue as private __parentByValue;
        validateConstraints as private __parentValidateConstraints;
    }

    /**
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function byValue(string $value): self
    {
        $characterCount = mb_strlen($value);
        if (1 !== $characterCount) {
            throw new Exception\Validation(
                sprintf(
                    'Value provided to build enum should have one character, ' .
                    'provided value has %d characters',
                    $characterCount
                )
            );
        }

        return static::__parentByValue($value);
    }

    /**
     * @throws Exception\Validation
     */
    protected static function validateConstraints(array $constraints): void
    {
        static::__parentValidateConstraints($constraints);

        foreach ($constraints as $name => $constraintValue) {
            if (1 !== mb_strlen($constraintValue)) {
                throw new Exception\Validation(
                    sprintf(
                        'Constraint %s have a value consisting with more than one char in enum %s.' .
                        'Consider using trait %s instead of %s if you need to work with non one char strings.',
                        $name,
                        static::class,
                        StringEnum::class,
                        static::class
                    )
                );
            }
        }
    }
}
