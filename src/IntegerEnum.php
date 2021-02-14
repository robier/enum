<?php

declare(strict_types = 1);

namespace Robier\Enum;

use Robier\Enum\Feature\Undefined;

trait IntegerEnum
{
    use StringEnum;

    /**
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     * @throws Exception\InvalidEnum
     */
    public static function byValue(int $value): self
    {
        static::setup();

        $index = array_search($value, self::$enumeration['values'], true);

        if (false === $index) {
            if (self::hasFeature(Undefined::class)) {
                return self::$enumeration['data'][Undefined::class]['enum'];
            }

            throw Exception\InvalidEnum::value(static::class, $value);
        }

        return static::cache($index);
    }

    /**
     * @inheritDoc
     */
    public static function byValues(int $value, int ...$values): Collection
    {
        array_unshift($values, $value);

        $enums = [];
        foreach($values as $value) {
            $enums[] = self::byValue($value);
        }

        return new Collection(...$enums);
    }

    /**
     * @inheritDoc
     */
    public function value(): int
    {
        if ($this->enumerationIndex === -1 && self::hasFeature(Undefined::class)) {
            return 0;
        }

        return static::$enumeration['values'][$this->enumerationIndex];
    }

    /**
     * @inheritDoc
     *
     * @return int[]
     */
    public static function getValues(): array
    {
        static::setup();

        return static::$enumeration['values'];
    }

    /**
     * @inheritDoc
     */
    protected static function validateConstraints(array $constraints): void
    {
        foreach ($constraints as $name => $constraintValue) {
            if (false === is_int($constraintValue)) {
                throw Exception\Validation::unexpectedConstantType(
                    static::class,
                    $name,
                    gettype($constraintValue),
                    'integer'
                );
            }

            if (
                self::hasFeature(Undefined::class)
                &&
                self::$enumeration['data'][Undefined::class]['name']->isSame($name)
            ) {
                throw Exception\Validation::undefinedConstDefined(static::class);
            }
        }

        if (count($constraints) !== count(array_flip($constraints))) {
            throw Exception\Validation::duplicatedConstantValues(static::class);
        }
    }
}
