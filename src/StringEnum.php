<?php

declare(strict_types = 1);

namespace Robier\Enum;

use InvalidArgumentException;
use LogicException;
use ReflectionClass;

trait StringEnum
{
    /**
     * @var array
     */
    private static $enumeration = [
        'initialized' => false,
        'values' => [],
        'names' => [],
        'cache' => [],
    ];

    /**
     * @var string
     */
    private $enumerationIndex;

    /**
     * @throws Exception\InvalidEnum
     */
    private function __construct(int $index)
    {
        $this->enumerationIndex = $index;
    }

    /**
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    protected static function setup(): void
    {
        if (static::$enumeration['initialized']) {
            return;
        }

        static::$enumeration['initialized'] = true;

        $constants = (new ReflectionClass(static::class))
            ->getConstants();

        if (empty($constants)) {
            throw new Exception\NoConstantsDefined(static::class);
        }

        static::validateConstraints($constants);

        $names = [];
        foreach ($constants as $key => $_) {
            $names[] = new Name($key, $key);
        }

        static::$enumeration['values'] = array_values($constants);
        static::$enumeration['names'] = $names;
    }

    /**
     * Validate all constraints defined in one enum.
     *
     * @throws Exception\Validation
     */
    protected static function validateConstraints(array $constraints): void
    {
        foreach ($constraints as $name => $constraint) {
            if (false === is_string($constraint)) {
                throw Exception\Validation::unexpectedConstantType(
                    static::class, $name, gettype($constraint), 'string'
                );
            }
        }

        if (count($constraints) !== count(array_flip($constraints))) {
            throw Exception\Validation::duplicatedConstantValues(static::class);
        }
    }

    /**
     * Create enum by name.
     *
     * @throws InvalidArgumentException
     * @throws Exception\NoConstantsDefined
     */
    public static function byName(string $name): self
    {
        static::setup();

        $enumIndex = null;

        /** @var Name $supportedName */
        foreach (static::$enumeration['names'] as $index => $supportedName) {
            if ($supportedName->isSame($name)) {
                $enumIndex = $index;
                break;
            }
        }

        if (null === $enumIndex) {
            throw Exception\InvalidEnum::name(static::class, $name);
        }

        return static::cache($enumIndex);
    }

    /**
     * Create enum by value.
     *
     * @throws InvalidArgumentException
     * @throws Exception\NoConstantsDefined
     */
    public static function byValue(string $value): self
    {
        static::setup();

        $index = array_search($value, self::$enumeration['values']);

        if (false === $index) {
            throw Exception\InvalidEnum::value(static::class, $value);
        }

        return static::cache($index);
    }

    /**
     * Create enum by index.
     *
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function byIndex(int $index): self
    {
        static::setup();

        if (!isset(self::$enumeration['values'][$index])) {
            throw Exception\InvalidEnum::index(static::class, $index);
        }

        return static::cache($index);
    }

    /**
     * Creates cache for current enumerations so every new enumeration is actually
     * reused object.
     */
    private static function cache(int $index): self
    {
        if (!isset(static::$enumeration['cache'][$index])) {
            $enumeration = new static($index);
            static::$enumeration['cache'][$index] = $enumeration;
        }

        return static::$enumeration['cache'][$index];
    }

    /**
     * Get all enums in an array.
     *
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function all(self ...$except): array
    {
        static::setup();

        if (!empty($except)) {
            $exceptValues = [];
            foreach ($except as $enum) {
                $exceptValues[] = $enum->value();
            }

            $values = array_diff(static::$enumeration['values'], $exceptValues);
        } else {
            $values = static::$enumeration['values'];
        }

        $enums = [];
        foreach ($values as $value) {
            $enums[] = static::byValue($value);
        }

        return $enums;
    }

    /**
     * @throws Exception\BadMethodCall
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        static::setup();

        /** @var Name $enumerationName */
        foreach (static::$enumeration['names'] as $index => $enumerationName) {
            if ($enumerationName->isSame($name)) {
                return static::cache($index);
            }
        }

        throw Exception\BadMethodCall::static(static::class, $name);
    }

    public function __call($methodName, $arguments)
    {
        if (strpos($methodName, 'is') !== 0) {
            throw Exception\BadMethodCall::instance(static::class, $methodName);
        }

        // get enum value
        $enumName = substr($methodName, 2);
        $enumIndex = null;

        /** @var Name $name */
        foreach (static::$enumeration['names'] as $index => $name) {
            if ($name->isSame($enumName)) {
                $enumIndex = $index;
                break;
            }
        }

        if (null === $enumIndex) {
            throw Exception\BadMethodCall::instance(static::class, $methodName);
        }

        return $this->enumerationIndex === $enumIndex;
    }

    /**
     * Get current enum name.
     */
    public function name(): Name
    {
        return static::$enumeration['names'][$this->enumerationIndex];
    }

    /**
     * Get all names defined for this enum.
     *
     * @return Name[]
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function getNames(): array
    {
        static::setup();

        return static::$enumeration['names'];
    }

    /**
     * Get current enum value.
     */
    public function value(): string
    {
        return static::$enumeration['values'][$this->enumerationIndex];
    }

    /**
     * @return string[]
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function getValues(): array
    {
        static::setup();

        return static::$enumeration['values'];
    }

    /**
     * Gets random enumeration value.
     *
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     * @throws Exception\InvalidRandom
     */
    public static function getRandom(self ...$except): self
    {
        $array = self::all(...$except);

        if (empty($array)) {
            throw new Exception\InvalidRandom();
        }

        $index = array_rand($array);

        return $array[$index];
    }

    /**
     * Check if current enum and provided enums are identical.
     */
    public function equal(self $enum): bool
    {
        return $this->enumerationIndex === $enum->enumerationIndex;
    }

    /**
     * Check if current enum is equal to any of provided enums.
     */
    public function any(self $enum1, self $enum2, self ...$enums): bool
    {
        array_unshift($enums, $enum1, $enum2);

        foreach ($enums as $enum) {
            if ($this->equal($enum)) {
                return true;
            }
        }

        return false;
    }

    public function __toString(): string
    {
        return (string)$this->name();
    }

    public function __sleep()
    {
        throw new LogicException('Enum can not be serialized');
    }

    public function __wakeup(): void
    {
        throw new LogicException('Enum can not be un-serialized');
    }

    public function __clone()
    {
        throw new LogicException('Enum can not be cloned');
    }
}
