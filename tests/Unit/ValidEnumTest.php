<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use PHPUnit\Framework\TestCase;
use Robier\Enum\CharEnum;
use Robier\Enum\Feature\Undefined;
use Robier\Enum\IntegerEnum;
use Robier\Enum\MaskEnum;
use Robier\Enum\Name;
use Robier\Enum\StringEnum;
use function Robier\Enum\isCharEnum;
use function Robier\Enum\isIntegerEnum;
use function Robier\Enum\isMaskEnum;
use function Robier\Enum\isStringEnum;
use function Robier\Enum\isUnsignedIntegerEnum;

/**
 * @covers \Robier\Enum\StringEnum
 * @covers \Robier\Enum\CharEnum
 * @covers \Robier\Enum\IntegerEnum
 * @covers \Robier\Enum\MaskEnum
 * @covers \Robier\Enum\Feature\Undefined
 * @runTestsInSeparateProcesses
 */
final class ValidEnumTest extends TestCase
{
    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allValidConstantNameAndValuePairs
     */
    public function testItWillReturnEnumIfValidNameProvidedToFactoryMethod(string $enumName, string $name, $value): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum|MaskEnum $enum */
        $enum = $enumName::byName($name);

        $this->assertSame($value, $enum->value());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs()
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testItWillReturnEnumIfValidValueProvidedToFactoryMethod(string $enumName, string $name, $value): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = $enumName::byValue($value);

        $this->assertTrue($enum->name()->isSame($name));
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allValidConstantNameAndValuePairs()
     */
    public function testItWillReturnEnumIfValidIndexProvidedToFactoryMethod(string $enumName, string $name, $value, int $index): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = $enumName::byIndex($index);

        $this->assertSame($value, $enum->value());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validStaticFactories()
     */
    public function testValidMagicFactories(string $enumName, string $method, $value): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = call_user_func([$enumName, $method]);

        $this->assertSame($value, $enum->value());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allValidConstantNameAndValuePairs()
     */
    public function testIsChecker(string $className, string $name): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = $className::byName($name);
        $isMethod = 'is' . Name::resolve($name)->pascalCase();

        $this->assertTrue($enum->{$isMethod}());

        /** @var IntegerEnum[]|StringEnum[]|CharEnum[] $all */
        $all = $className::all($enum);
        foreach($all as $itemEnum) {
            $name = isMaskEnum($itemEnum) ? $itemEnum->names()[0] : $itemEnum->name();
            $isMethodName = 'is' . $name->pascalCase();

            $this->assertFalse($enum->{$isMethodName}());
        }
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allValidConstantNameAndValuePairs()
     */
    public function testNotChecker(string $className, string $name): void
    {
        /** @var IntegerEnum|StringEnum|CharEnum $enum */
        $enum = $className::byName($name);
        $notMethod = 'not' . Name::resolve($name)->pascalCase();

        $this->assertFalse($enum->{$notMethod}());

        /** @var IntegerEnum[]|StringEnum[]|CharEnum[] $all */
        $all = $className::all($enum);
        foreach($all as $itemEnum) {
            $name = isMaskEnum($itemEnum) ? $itemEnum->names()[0] : $itemEnum->name();
            $notMethodName = 'not' . $name->pascalCase();
            $this->assertTrue($enum->{$notMethodName}());
        }
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allValidValues()
     */
    public function testGettingAllValues(string $className, array $values): void
    {
        $this->assertSame(
            $values,
            $className::getValues()
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allValidNames()
     */
    public function testGettingAllNames(string $className, array $names): void
    {
        $this->assertEquals(
            $names,
            $className::getNames()
        );
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerations()
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
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsExceptOne()
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
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validConstantNameAndValuePairs()
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testItReturnsAlwaysSameEnumObjectInstance(string $class, string $name): void
    {
        $enum1 = $class::byName($name);
        $enum2 = $class::byName($name);

        $this->assertSame($enum1, $enum2);
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testItReturnsUndefinedEnumObjectWhenNotExistingIndexProvided(string $class): void
    {
        /** @var Undefined $enum */
        $enum = $class::byIndex(1000);

        $this->assertTrue($enum->isUndefined());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testItReturnsUndefinedEnumObjectWhenNotExistingNameProvided(string $class): void
    {
        /** @var Undefined $enum */
        $enum = $class::byName('not-existing-name');

        $this->assertTrue($enum->isUndefined());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testItReturnsUndefinedEnumObjectWhenNotExistingValueProvided(string $class): void
    {
        /** @var Undefined $enum */
        if (isStringEnum($class)) {
            $enum = $class::byValue('not-existing-name');
        }

        if (isCharEnum($class)) {
            $enum = $class::byValue('X');
        }

        if (isIntegerEnum($class)) {
            $enum = $class::byValue(10000);
        }

        if (isUnsignedIntegerEnum($class)) {
            $enum = $class::byValue(10000);
        }

        if (!isset($enum)) {
            $this->fail('Provided enum type not covered in test');
        }

        $this->assertTrue($enum->isUndefined());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testUndefinedFactoryWorking(string $class): void
    {
        /** @var Undefined $enum */
        $enum = $class::undefined();

        $this->assertTrue($enum->isUndefined());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testTwoUndefinedEnumsAreNotEqual(string $class): void
    {
        /** @var Undefined $enum1 */
        /** @var Undefined $enum2 */
        $enum1 = $class::undefined();
        $enum2 = $class::undefined();

        $this->assertFalse($enum1->equal($enum2));
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testOneUndefinedEnumIsNotEqualToOthers(string $class, string $name): void
    {
        /** @var Undefined $enum1 */
        /** @var Undefined $enum2 */
        $enum1 = $class::byName($name);
        $enum2 = $class::undefined();

        $this->assertFalse($enum1->equal($enum2));
        $this->assertFalse($enum2->equal($enum1));
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testUndefinedEnumIsNotUndefinedIfCreatedProperly(string $class, string $name): void
    {
        /** @var Undefined $enum */
        $enum = $class::byName($name);

        $this->assertFalse($enum->isUndefined());
        $this->assertTrue($enum->notUndefined());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testUndefinedEnumWillReturnEmptyValue(string $class): void
    {
        /** @var Undefined $enum */
        $enum = $class::byName('un-defined-name');

        $this->assertEmpty($enum->value());

        if (isCharEnum($enum) || isStringEnum($enum)) {
            $this->assertSame('', $enum->value());
            return;
        }

        if (isIntegerEnum($enum) || isUnsignedIntegerEnum($enum)) {
            $this->assertSame(0, $enum->value());
            return;
        }

        $this->fail('Could not test enum ' . $class);
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::validUndefinedEnumNameAndValuePairs()
     */
    public function testUndefinedEnumWillReturnName(string $class): void
    {
        /** @var Undefined $enum */
        $enum = $class::byName('un-defined-name');

        $this->assertSame('UNDEFINED', (string)$enum->name());
    }
}
