<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit\Functions;

use Generator;
use Robier\Enum;
use Robier\Enum\Test\Data;
use stdClass;

final class DataProvider
{
    public static function invalidEnums(): Generator
    {
        yield [stdClass::class];
        yield [new stdClass];
        yield ['NotExistingClass'];
    }

    public static function stringEnums(): Generator
    {
        yield [Data\Strings\ValidStringEnum::class];
        yield [Data\Strings\ValidStringEnum::getRandom()];
    }

    public static function charEnums(): Generator
    {
        yield [Data\Chars\ValidCharEnum::class];
        yield [Data\Chars\ValidCharEnum::getRandom()];
    }

    public static function integerEnums(): Generator
    {
        yield [Data\Integers\ValidIntegerEnum::class];
        yield [Data\Integers\ValidIntegerEnum::getRandom()];
    }

    public static function unsignedIntegerEnums(): Generator
    {
        yield [Data\UnsignedIntegers\ValidUnsignedIntegerEnum::class];
        yield [Data\UnsignedIntegers\ValidUnsignedIntegerEnum::getRandom()];
    }

    public static function maskEnum(): Generator
    {
        yield [Data\Masks\ValidMaskEnum::class];
        yield [Data\Masks\ValidMaskEnum::getRandom()];
    }

    public static function stringHierarchicalEnums(): Generator
    {
        yield [Data\MultipleLevelsHierarchyEnum::class];
        yield [Data\MultipleLevelsHierarchyEnum::getRandom()];

        yield [Data\OneLevelHierarchyEnum::class];
        yield [Data\OneLevelHierarchyEnum::getRandom()];
    }

    public static function getEnumType(): Generator
    {
        $data = [
            Enum\StringEnum::class => self::connectGenerators(
                static::stringEnums(),
                static::stringHierarchicalEnums()
            ),
            Enum\CharEnum::class => static::charEnums(),
            Enum\IntegerEnum::class => static::integerEnums(),
            Enum\UnsignedIntegerEnum::class => static::unsignedIntegerEnums(),
            Enum\MaskEnum::class => static::maskEnum(),
        ];

        foreach ($data as $type => $generator) {
            foreach ($generator as $enum) {
                yield [$type, $enum[0]];
            }
        }
    }

    private static function connectGenerators(Generator ...$generators): iterable
    {
        foreach ($generators as $generator) {
            yield from $generator;
        }
    }
}
