<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use Generator;
use Robier\Enum\Test\Data\Chars\BadTypeCharEnumValues;
use Robier\Enum\Test\Data\Chars\DuplicatedCharEnumValues;
use Robier\Enum\Test\Data\Chars\NoConstantsDefinedCharEnum;
use Robier\Enum\Test\Data\Chars\ValidCharEnum;
use Robier\Enum\Test\Data\Integers\BadTypeIntegerEnumValues;
use Robier\Enum\Test\Data\Integers\DuplicatedIntegerEnumValues;
use Robier\Enum\Test\Data\Integers\NoConstantsDefinedIntegerEnum;
use Robier\Enum\Test\Data\Masks\BadTypeMaskEnumValues;
use Robier\Enum\Test\Data\Masks\DuplicatedMaskEnumValues;
use Robier\Enum\Test\Data\Masks\NoConstantsDefinedMaskEnum;
use Robier\Enum\Test\Data\Integers\ValidIntegerEnum;
use Robier\Enum\Test\Data\Masks\ValidMaskEnum;
use Robier\Enum\Test\Data\Strings\BadTypeStringEnumValues;
use Robier\Enum\Test\Data\Strings\DuplicatedStringEnumValues;
use Robier\Enum\Test\Data\Strings\NoConstantsDefinedStringEnum;
use Robier\Enum\Test\Data\Strings\ValidStringEnum;
use Robier\Enum\Test\Data\UnsignedIntegers\BadTypeUnsignedIntegerEnumValues;
use Robier\Enum\Test\Data\UnsignedIntegers\DuplicatedUnsignedIntegerEnumValues;
use Robier\Enum\Test\Data\UnsignedIntegers\NoConstantsDefinedUnsignedIntegerEnum;
use Robier\Enum\Test\Data\UnsignedIntegers\ValidUnsignedIntegerEnum;

/**
 * @covers \Robier\Enum\StringEnum
 * @covers \Robier\Enum\CharEnum
 * @covers \Robier\Enum\IntegerEnum
 * @covers \Robier\Enum\UnsignedIntegerEnum
 * @covers \Robier\Enum\Masks
 */
final class InvalidDataProvider
{
    private const VALID_ENUMS = [
        ValidStringEnum::class,
        ValidIntegerEnum::class,
        ValidUnsignedIntegerEnum::class,
        ValidCharEnum::class,
        ValidMaskEnum::class,
    ];

    public static function notExistingEnumValues(): Generator
    {
        yield [
            ValidStringEnum::class,
            'invalid-value'
        ];

        yield [
            ValidIntegerEnum::class,
            155
        ];

        yield [
            ValidUnsignedIntegerEnum::class,
            155
        ];

        yield [
            ValidCharEnum::class,
            't'
        ];
    }

    public static function notExistingEnumNames(): Generator
    {
        foreach (self::VALID_ENUMS as $enumItem) {
            yield [$enumItem, 'not-existing-name'];
        }
    }

    public static function invalidInstanceMethods(): Generator
    {
        $data = [
            'notDefinedEnumOrMethod',
            'isNotDefinedEnumOrMethod',
        ];

        foreach (self::VALID_ENUMS as $enumItem) {
            foreach ($data as $dataItem) {
                yield [
                    $enumItem, $dataItem
                ];
            }
        }
    }

    public static function invalidStaticMethods(): Generator
    {
        $data = [
            'notDefinedEnumOrMethod',
        ];

        foreach (self::VALID_ENUMS as $enumItem) {
            foreach ($data as $dataItem) {
                yield [
                    $enumItem, $dataItem
                ];
            }
        }
    }

    public function duplicatedEnumValues(): Generator
    {
        yield [DuplicatedStringEnumValues::class];

        yield [DuplicatedIntegerEnumValues::class];

        yield [DuplicatedUnsignedIntegerEnumValues::class];

        yield [DuplicatedCharEnumValues::class];

        yield [DuplicatedMaskEnumValues::class];
    }

    public function badEnumValueTypes(): Generator
    {
        yield [BadTypeStringEnumValues::class, 'ONE', 'string', 'integer'];

        yield [BadTypeIntegerEnumValues::class, 'TEST', 'integer', 'string'];

        yield [BadTypeUnsignedIntegerEnumValues::class, 'TEST', 'integer', 'string'];

        yield [BadTypeCharEnumValues::class, 'NEW', 'string', 'integer'];

        yield [BadTypeMaskEnumValues::class, 'TEST', 'integer', 'string'];
    }

    public function noConstantsDefinedEnums(): Generator
    {
        yield [NoConstantsDefinedStringEnum::class];

        yield [NoConstantsDefinedIntegerEnum::class];

        yield [NoConstantsDefinedUnsignedIntegerEnum::class];

        yield [NoConstantsDefinedCharEnum::class];

        yield [NoConstantsDefinedMaskEnum::class];
    }
}
