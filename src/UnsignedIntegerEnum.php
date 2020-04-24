<?php

declare(strict_types = 1);

namespace Robier\Enum;

trait UnsignedIntegerEnum
{
    use IntegerEnum {
        byValue as private __parentByValue;
        validateConstraints as private __parentValidateConstraints;
    }

    /**
     * @inheritDoc
     */
    public static function byValue(int $value): self
    {
        if ($value < 0) {
            throw new Exception\Validation(
                sprintf(
                    'Value provided to build enum should be equal or greater than 0, provided value is %d',
                    $value
                )
            );
        }

        return static::__parentByValue($value);
    }

    /**
     * @inheritDoc
     */
    protected static function validateConstraints(array $constraints): void
    {
        foreach ($constraints as $name => $constraintValue) {
            if ($constraintValue < 0) {
                throw new Exception\Validation(
                    sprintf(
                        'Constraint %s have a value %d that is lower than 0.' .
                        'Consider using trait %s instead of %s if you need to work with values that are lower then 0.',
                        $name,
                        $constraintValue,
                        IntegerEnum::class,
                        static::class
                    )
                );
            }
        }

        static::__parentValidateConstraints($constraints);
    }
}
