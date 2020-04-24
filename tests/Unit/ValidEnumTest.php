<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use PHPUnit\Framework\TestCase;
use Robier\Enum\CharEnum;
use Robier\Enum\IntegerEnum;
use Robier\Enum\MaskEnum;
use Robier\Enum\StringEnum;

/**
 * @covers \Robier\Enum\StringEnum
 * @covers \Robier\Enum\CharEnum
 * @covers \Robier\Enum\IntegerEnum
 * @covers \Robier\Enum\MaskEnum
 * @runTestsInSeparateProcesses
 */
class ValidEnumTest extends TestCase
{
    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validMaskEnumNameAndValuePairs
     */
    public function testItWillReturnEnumIfValidNameProvidedToFactoryMethod(string $enumName, string $name, $value): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum|MaskEnum $enum */
        $enum = $enumName::byName($name);

        $this->assertSame($value, $enum->value());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs
     */
    public function testItWillReturnEnumIfValidValueProvidedToFactoryMethod(string $enumName, string $name, $value): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = $enumName::byValue($value);

        $this->assertTrue($enum->name()->isSame($name));
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validMaskEnumNameAndValuePairs
     */
    public function testItWillReturnEnumIfValidIndexProvidedToFactoryMethod(string $enumName, string $name, $value, int $index): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = $enumName::byIndex($index);

        $this->assertSame($value, $enum->value());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validStaticFactories
     */
    public function testValidMagicFactories(string $enumName, string $method, $value): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = call_user_func([$enumName, $method]);

        $this->assertSame($value, $enum->value());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validIsMethods
     */
    public function testCheckers(string $className, string $name, string $isMethod): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = $className::byName($name);

        $this->assertTrue($enum->{$isMethod}());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allValidValues
     */
    public function testGettingAllValues(string $className, array $values): void
    {
        $this->assertSame(
            $values,
            $className::getValues()
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allValidNames
     */
    public function testGettingAllNames(string $className, array $names): void
    {
        $this->assertEquals(
            $names,
            $className::getNames()
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerations
     */
    public function testGettingAllEnumerations(string $className, array $allNames): void
    {
        $all = array_map(static function (string $name) use ($className) {
            return $className::byName($name);
        }, $allNames);

        $this->assertEquals(
            $all,
            $className::all()
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsExceptOne
     */
    public function testGettingSomeEnumerations(string $className, array $expected, array $exclude): void
    {
        $expected = array_map(static function (string $name) use ($className) {
            return $className::byName($name);
        }, $expected);

        $exclude = array_map(static function (string $name) use ($className) {
            return $className::byName($name);
        }, $exclude);

        $this->assertEquals(
            $expected,
            $className::all(...$exclude)
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::equals()
     */
    public function testIsEqualPassingCheck(string $class, string $name): void
    {
        $this->assertTrue(
            $class::byName($name)->equal($class::byName($name))
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::equals()
     */
    public function testSameInstance(string $class, string $name): void
    {
        $this->assertSame(
            $class::byName($name),
            $class::byName($name)
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::notEqual()
     */
    public function testIsEqualNotPassingCheck(string $className, string $firstName, string $secondName): void
    {
        $this->assertFalse(
            $className::byName($firstName)->equal($className::byName($secondName))
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::toString()
     */
    public function testItCanBeConvertedToString(string $enumeration, string $expected): void
    {
        $this->assertSame(
            $expected,
            (string)$enumeration::byName($expected)
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::isAnyPassing()
     */
    public function testIsAnyMethodPassing(string $className, string $existingName, array $names): void
    {
        $collection = array_map(static function (string $name) use ($className) {
            return $className::byName($name);
        }, $names);

        $this->assertTrue(
            $className::byName($existingName)->any(...$collection)
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::isAnyNotPassing()
     */
    public function testIsAnyMethodNotPassing(string $className, string $notExistingName, array $names): void
    {
        $collection = array_map(static function (string $name) use ($className) {
            return $className::byName($name);
        }, $names);

        $this->assertFalse(
            $className::byName($notExistingName)->any(...$collection)
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerations()
     */
    public function testRandomMethod(string $className, array $allNames): void
    {
        $all = array_map(static function (string $name) use ($className) {
            return $className::byName($name);
        }, $allNames);

        $this->assertTrue(
            $className::getRandom()->any(...$all)
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs
     */
    public function testItReturnsAlwaysSameEnumObjectInstance(string $class, string $name): void
    {
        $enum1 = $class::byName($name);
        $enum2 = $class::byName($name);

        $this->assertSame($enum1, $enum2);
    }
}
