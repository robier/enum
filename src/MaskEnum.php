<?php

declare(strict_types = 1);

namespace Robier\Enum;

use LogicException;
use ReflectionClass;
use Robier\Enum\Feature\Undefined;

trait MaskEnum
{
    /**
     * @var array
     */
    private static $enumeration = [
        'initialized' => false,
        'undefined' => null,
        'values' => [],
        'names' => [],
        'all' => 0,
    ];

    /**
     * @var int
     */
    protected $enumerationValue;

    /**
     * @throws Exception\Validation
     */
    protected function __construct(?int $value)
    {
        if (null === $value) {
            return;
        }

        // validate value
        if ($value < 0) {
            throw new Exception\Validation(
                sprintf(
                    'Mask value must not be negative, provided to enum %s',
                    static::class
                )
            );
        }

        if ($value > static::$enumeration['all']) {
            throw new Exception\Validation(
                sprintf(
                    'Provided value %d should not be grater than max (%d) possible in enum %s',
                    $value,
                    static::$enumeration['all'],
                    static::class
                )
            );
        }

        $this->enumerationValue = $value;
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

        if (in_array(Undefined::class, $reflection->getTraitNames())) {
            static::$enumeration['undefined'] = [
                'name' => new Name('UNDEFINED', 'UNDEFINED'),
                'enum' => new static(null),
            ];
        }

        static::validateConstraints($constants);

        $names = [];
        $all = 0;
        foreach ($constants as $name => $value) {
            $all |= $value;

            $names[] = Name::resolve($name);
        }

        static::$enumeration['values'] = array_values($constants);
        static::$enumeration['names'] = $names;
        static::$enumeration['all'] = $all;
    }

    /**
     * @throws Exception\Validation
     */
    protected static function validateConstraints(array $constraints): void
    {
        foreach ($constraints as $name => $constraint) {
            if (false === is_int($constraint)) {
                throw Exception\Validation::unexpectedConstantType(
                    static::class,
                    $name,
                    gettype($constraint),
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

    /**
     * Create instance by name, or multiple names
     *
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function byName(string $name, string ...$names): self
    {
        static::setup();

        array_unshift($names, $name);

        $names = array_unique($names);

        $names = array_map([Name::class, 'resolve'], $names);

        $sum = 0;
        /** @var Name $name */
        foreach ($names as $name) {
            $index = array_search($name->upperSnakeCase(), static::$enumeration['names']);

            if ($index === false) {
                throw Exception\InvalidEnum::name(static::class, $name->original());
            }

            if (isset(static::$enumeration['values'][$index])) {
                $sum += static::$enumeration['values'][$index];
                continue;
            }
        }

        return new static($sum);
    }

    /**
     * Create instance by value, or multiple values
     *
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function byValue(int $value, int ...$values): self
    {
        static::setup();

        $values[] = $value;

        $sum = array_sum($values);

        return new static($sum);
    }

    /**
     * Create instance by index or multiple indices
     */
    public static function byIndex(int $index, int ...$indices): self
    {
        static::setup();

        $indices[] = $index;
        $value = 0;
        foreach ($indices as $index) {
            if (!isset(self::$enumeration['values'][$index])) {
                throw Exception\InvalidEnum::index(static::class, $index);
            }
            $value += self::$enumeration['values'][$index];
        }

        return new static($value);
    }

    /**
     * Get value
     */
    public function value(): int
    {
        if (static::$enumeration['undefined'] && null === $this->enumerationValue) {
            return 0;
        }

        return $this->enumerationValue;
    }

    /**
     * Check if current instance value is the same as of provided enum
     */
    public function equal(self $enum): bool
    {
        if (static::$enumeration['undefined'] && null === $this->enumerationValue) {
            return false;
        }

        if (static::$enumeration['undefined'] && null === $enum->enumerationValue) {
            return false;
        }

        return $this->value() === $enum->value();
    }

    /**
     * Check if current instance contains provided value
     */
    public function contains(self $enum): bool
    {
        if (static::$enumeration['undefined'] && null === $this->enumerationValue) {
            return false;
        }

        if (static::$enumeration['undefined'] && null === $enum->enumerationValue) {
            return false;
        }

        return (bool)($this->value() & $enum->value());
    }

    /**
     * Get all possible values, every in separate instance
     *
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function all(self ...$except): array
    {
        static::setup();

        $all = [];
        foreach (self::$enumeration['values'] as $value) {
            foreach ($except as $exceptEnum) {
                if ($value & $exceptEnum->value()) {
                    continue 2;
                }
            }

            $all[] = self::byValue($value);
        }

        return $all;
    }

    /**
     * Get enum instance that contains all possible values of certain type
     *
     * @throws Exception\NoConstantsDefined
     * @throws Exception\Validation
     */
    public static function allInOne(self ...$except): self
    {
        static::setup();

        $sum = 0;
        foreach ($except as $enum) {
            $sum |= $enum->value();
        }

        return new static(static::$enumeration['all'] - $sum);
    }

    /**
     * Check if current enum contains any of provided enum values
     */
    public function any(self $enum1, self $enum2, self ...$enums): bool
    {
        array_unshift($enums, $enum1, $enum2);

        foreach ($enums as $enum) {
            if ($this->contains($enum)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if current enum contains all provided enum values
     */
    public function containsAll(self $enum1, self $enum2, self ...$enums): bool
    {
        array_unshift($enums, $enum1, $enum2);

        $counter = 0;
        foreach ($enums as $enum) {
            if ($this->contains($enum)) {
                ++$counter;
            }
        }

        return func_num_args() === $counter;
    }

    /**
     * @throws Exception\BadMethodCall
     * @throws Exception\NoConstantsDefined
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        static::setup();

        /** @var Name $enumerationName */
        foreach (static::$enumeration['names'] as $index => $enumerationName) {
            if ($enumerationName->isSame($name)) {
                return new static(static::$enumeration['values'][$index]);
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

        return 0 !== ($this->enumerationValue & static::$enumeration['values'][$enumIndex]);
    }

    /**
     * Get random enum
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
     * @return string[]
     * @throws Exception\NoConstantsDefined
     */
    public static function getNames(): array
    {
        static::setup();

        return static::$enumeration['names'];
    }

    /**
     * @return string[]
     * @throws Exception\NoConstantsDefined
     */
    public static function getValues(): array
    {
        static::setup();

        return static::$enumeration['values'];
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
