<?php

declare(strict_types = 1);

namespace Robier\Enum;

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
            if (self::$enumeration['undefined']) {
                return self::$enumeration['undefined']['enum'];
            }

            throw Exception\InvalidEnum::value(static::class, $value);
        }

        return static::cache($index);
    }

    /**
     * @inheritDoc
     */
    public function value(): int
    {
        if (static::$enumeration['undefined'] && $this->enumerationIndex === -1) {
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

            if (self::$enumeration['undefined'] && self::$enumeration['undefined']['name']->isSame($name)) {
                throw Exception\Validation::undefinedConstDefined(static::class);
            }
        }

        if (count($constraints) !== count(array_flip($constraints))) {
            throw Exception\Validation::duplicatedConstantValues(static::class);
        }
    }
}
