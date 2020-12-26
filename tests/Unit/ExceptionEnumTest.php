<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use LogicException;
use PHPUnit\Framework\TestCase;
use Robier\Enum\Exception\BadMethodCall;
use Robier\Enum\Exception\InvalidEnum;
use Robier\Enum\Exception\InvalidRandom;
use Robier\Enum\Exception\NoConstantsDefined;
use Robier\Enum\Exception\Validation;
use Robier\Enum\IntegerEnum;
use Robier\Enum\StringEnum;
use Robier\Enum\Test\Data\Chars\InvalidValueCharEnum;
use Robier\Enum\Test\Data\Chars\ValidCharEnum;
use Robier\Enum\Test\Data\Masks\ValidMaskEnum;
use Robier\Enum\Test\Data\UnsignedIntegers\InvalidValueUnsignedIntegerEnum;
use Robier\Enum\Test\Data\UnsignedIntegers\ValidUnsignedIntegerEnum;

/**
 * @covers \Robier\Enum\StringEnum
 * @covers \Robier\Enum\CharEnum
 * @covers \Robier\Enum\IntegerEnum
 * @covers \Robier\Enum\UnsignedIntegerEnum
 * @covers \Robier\Enum\MaskEnum
 * @covers \Robier\Enum\Feature\Undefined
 */
class ExceptionEnumTest extends TestCase
{
    /**
     * @dataProvider \Robier\Enum\Test\Unit\InvalidDataProvider::invalidInstanceMethods()
     */
    public function testItThrowsExceptionWhenUndefinedInstanceMethodIsCalled(string $className, string $method): void
    {
        $this->expectException(BadMethodCall::class);
        $this->expectExceptionMessage(BadMethodCall::instance($className, $method)->getMessage());

        $className::getRandom()->{$method}();
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\InvalidDataProvider::invalidStaticMethods()
     */
    public function testItThrowsExceptionWhenUndefinedClassMethodIsCalled(string $className, string $method): void
    {
        $this->expectException(BadMethodCall::class);
        $this->expectExceptionMessage(BadMethodCall::static($className, $method)->getMessage());

        $className::{$method}();
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\InvalidDataProvider::notExistingEnumValues()
     */
    public function testItThrowsExceptionWhenUndefinedValueProvidedToValueFactory(string $className, $value): void
    {
        $this->expectException(InvalidEnum::class);
        $this->expectExceptionMessage(InvalidEnum::value($className, $value)->getMessage());

        $className::byValue($value);
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\InvalidDataProvider::notExistingEnumNames()
     */
    public function testItThrowsExceptionWhenUndefinedNameProvidedToNameFactory(string $className, string $name): void
    {
        $this->expectException(InvalidEnum::class);
        $this->expectExceptionMessage(InvalidEnum::name($className, $name)->getMessage());

        $className::byName($name);
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\InvalidDataProvider::duplicatedEnumValues()
     */
    public function testItThrowsExceptionWhenDuplicatedValueDefined(string $className): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(Validation::duplicatedConstantValues($className)->getMessage());

        $className::all();
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\InvalidDataProvider::badEnumValueTypes()
     */
    public function testItThrowsExceptionWhenEnumValueHasBadVariableType(string $className, string $constant, string $expectedType, string $actualType): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(Validation::unexpectedConstantType($className, $constant, $actualType, $expectedType)->getMessage());

        $className::all();
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\InvalidDataProvider::noConstantsDefinedEnums()
     */
    public function testItThrowsExceptionWhenNoConstantsDefinedInEnum(string $className): void
    {
        $this->expectException(NoConstantsDefined::class);
        $this->expectExceptionMessage((new NoConstantsDefined($className))->getMessage());

        $className::all();
    }

    public function testItTrowsExceptionWhenNegativeValueSetInUnsignedIntegerEnum(): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(
            (
            new Validation(
                sprintf(
                    'Constraint %s have a value %d that is lower than 0.' .
                    'Consider using trait %s instead of %s if you need to work with values that are lower then 0.',
                    'ONE',
                    -1,
                    IntegerEnum::class,
                    InvalidValueUnsignedIntegerEnum::class
                )
            )
            )->getMessage()
        );

        InvalidValueUnsignedIntegerEnum::all();
    }

    public function testItTrowsExceptionWhenMultipleCharValueSetInCharEnum(): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(
            (
                new Validation(
                    sprintf(
                    'Constraint %s have a value consisting with more than one char in enum %s.'.
                    'Consider using trait %s instead of %s if you need to work with non one char strings.',
                    'ONE',
                        InvalidValueCharEnum::class,
                        StringEnum::class,
                        InvalidValueCharEnum::class
                    )
                )
            )->getMessage()
        );

        InvalidValueCharEnum::all();
    }

    public function testItTrowsExceptionWhenInvalidValueProvidedToUnsignedInteger(): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(
            (
            new Validation(
                sprintf(
                    'Value provided to build enum should be equal or greater than 0, provided value is %d',
                    -5
                )
            )
            )->getMessage()
        );

        ValidUnsignedIntegerEnum::byValue(-5);
    }

    public function testItTrowsExceptionWhenInvalidValueProvidedToCharEnum(): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(
            (
                new Validation(
                    sprintf(
                        'Value provided to build enum should have one character, provided value has %d characters',
                        3
                    )
                )
            )->getMessage()
        );

        ValidCharEnum::byValue('abc');
    }

    public function testItTrowsExceptionWhenNegativeValueProvidedToMaskEnum(): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(
            (
            new Validation(
                sprintf(
                    'Mask value must not be negative, provided to enum %s',
                    ValidMaskEnum::class
                )
            )
            )->getMessage()
        );

        ValidMaskEnum::byValue(-1);
    }

    public function testItTrowsExceptionWhenTooBigValueProvidedToMaskEnum(): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(
            (
            new Validation(
                sprintf(
                    'Provided value %d should not be grater than max (%d) possible in enum %s',
                    999,
                    7,
                    ValidMaskEnum::class
                )
            )
            )->getMessage()
        );

        ValidMaskEnum::byValue(999);
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs()
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validMaskEnumNameAndValuePairs()
     */
    public function testItThrowsExceptionWhenEnumIsCloned(string $class): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum can not be cloned');

        $instance = $class::getRandom();

        clone $instance;
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs()
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validMaskEnumNameAndValuePairs()
     */
    public function testItThrowsExceptionWhenAllPossibilitiesAreExcluded(string $class): void
    {
        $this->expectException(InvalidRandom::class);
        $this->expectExceptionMessage('Can not return random value if all possibilities are excluded');

        $class::getRandom(...$class::all());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs()
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validMaskEnumNameAndValuePairs()
     */
    public function testItThrowsExceptionWhenEnumIsSerialized(string $class): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum can not be serialized');

        $instance = $class::getRandom();

        serialize($instance);
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::serializedEnums()
     */
    public function testItThrowsExceptionWhenEnumIsUnSerialized(string $string): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Enum can not be un-serialized');

        unserialize($string);
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs()
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validMaskEnumNameAndValuePairs()
     */
    public function testItThrowsExceptionWhenInvalidIndexProvided(string $class): void
    {
        $this->expectException(InvalidEnum::class);
        $this->expectExceptionMessage(InvalidEnum::index($class, 999)->getMessage());

        $class::byIndex(999);
    }
    /**
     * @dataProvider \Robier\Enum\Test\Unit\InvalidDataProvider::invalidUndefinedEnums()
     */
    public function testItThrowsExceptionWhenUndefinedConstantDefined(string $class): void
    {
        $this->expectException(Validation::class);
        $this->expectExceptionMessage(Validation::undefinedConstDefined($class)->getMessage());

        $class::byIndex(999);
    }
}
