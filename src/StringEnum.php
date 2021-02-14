<?php

declare(strict_types = 1);

namespace Robier\Enum;

use InvalidArgumentException;
use LogicException;
use ReflectionClass;
use Robier\Enum\Feature\Undefined;

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
        // available features that can be enabled by using trait
        'features' => [
            Undefined::class => false,
        ],
        // some features will have some additional data, all additional data
        // should go in this array, key needs to be feature name
        'data' => [
            Undefined::class => [
                'name' => null,
                'enum' => null,
            ],
        ],
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

        $reflection = new ReflectionClass(static::class);

        $constants = $reflection
            ->getConstants();

        if (empty($constants)) {
            throw new Exception\NoConstantsDefined(static::class);
        }

        $usedTraits = $reflection->getTraitNames();
        foreach(self::$enumeration['features'] as $feature => $_) {
            self::$enumeration['features'][$feature] = in_array($feature, $usedTraits, true);
        }

        if (self::hasFeature(Undefined::class)) {
            static::$enumeration['data'][Undefined::class] = [
                'name' => new Name('UNDEFINED', 'UNDEFINED'),
                'enum' => new static(-1),
            ];
        }

        static::validateConstraints($constants);

        $names = [];
        foreach ($constants as $key => $_) {
            $names[] = new Name($key, $key);
        }

        static::$enumeration['values'] = array_values($constants);
        static::$enumeration['names'] = $names;
    }

    public static function hasFeature(string $feature): bool
    {
        static::setup();

        return self::$enumeration['features'][$feature] ?? false;
    }

    public static function supportsFeature(string $feature): bool
    {
        return array_key_exists($feature, self::$enumeration['features']);
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
            if (self::hasFeature(Undefined::class)) {
                return self::$enumeration['data'][Undefined::class]['enum'];
            }

            throw Exception\InvalidEnum::name(static::class, $name);
        }

        return static::cache($enumIndex);
    }

    /**
     * Create collection of enum by names.
     *
     * @throws Exception\NoConstantsDefined
     */
    public static function byNames(string $name, string ...$names): Collection
    {
        array_unshift($names, $name);

        $enums = [];
        foreach($names as $name) {
            $enums[] = self::byName($name);
        }

        return new Collection(...$enums);
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
            if (self::hasFeature(Undefined::class)) {
                return self::$enumeration['data'][Undefined::class]['enum'];
            }

            throw Exception\InvalidEnum::value(static::class, $value);
        }

        return static::cache($index);
    }

    /**
     * Create collection of enum by values.
     *
     * @throws Exception\NoConstantsDefined
     */
    public static function byValues(string $value, string ...$values): Collection
    {
        array_unshift($values, $value);

        $enums = [];
        foreach ($values as $value) {
            $enums[] = self::byValue($value);
        }

        return new Collection(...$enums);
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

        if ($index < 0) {
            throw Exception\InvalidEnum::negativeIndex(static::class);
        }

        if (!isset(self::$enumeration['values'][$index])) {
            if (self::hasFeature(Undefined::class)) {
                return self::$enumeration['data'][Undefined::class]['enum'];
            }

            throw Exception\InvalidEnum::index(static::class, $index);
        }

        return static::cache($index);
    }

    /**
     * Create collection of enum by indexes.
     *
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function byIndexes(int $index, int ...$indexes): Collection
    {
        array_unshift($indexes, $index);

        $enums = [];
        foreach($indexes as $index) {
            $enums[] = self::byIndex($index);
        }

        return new Collection(...$enums);
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
        $type = null;
        if (strpos($methodName, 'not') === 0) {
            $type = 'not';
        }

        if (strpos($methodName, 'is') === 0) {
            $type = 'is';
        }

        if ($type === null) {
            throw Exception\BadMethodCall::instance(static::class, $methodName);
        }

        // get enum value
        $enumName = substr($methodName, strlen($type));
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

        if ($type === 'is') {
            return $this->enumerationIndex === $enumIndex;
        }

        return $this->enumerationIndex !== $enumIndex;
    }

    /**
     * Get current enum name.
     */
    public function name(): Name
    {
        if (self::hasFeature(Undefined::class) && $this->enumerationIndex === -1) {
            return static::$enumeration['data'][Undefined::class]['name'];
        }

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
        if ($this->enumerationIndex === -1 && self::hasFeature(Undefined::class)) {
            return '';
        }

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
        if (self::hasFeature(Undefined::class)) {
            if ($this->enumerationIndex === -1) {
                return false;
            }

            if ($enum->enumerationIndex === -1) {
                return false;
            }
        }

        return $this->enumerationIndex === $enum->enumerationIndex;
    }

    /**
     * Check if current enum is equal to any of provided enums.
     */
    public function any(self ...$enums): bool
    {
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
