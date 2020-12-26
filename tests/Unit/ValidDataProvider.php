<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use Generator;
use Robier\Enum\Name;
use Robier\Enum\Test\Data;

final class ValidDataProvider
{
    public static function validConstantNameAndValuePairs(): Generator
    {
        yield [
            Data\Strings\ValidStringEnum::class, // name
            'ONE', // constant
            'one text', // value
            0, // index
        ];

        yield [
            Data\Strings\ValidStringEnum::class,
            'ONE_TWO',
            'one two text',
            1,
        ];

        yield [
            Data\Strings\ValidStringEnum::class,
            'ONE_TWO_THREE',
            'one two three text',
            2,
        ];

        yield [
            Data\Integers\ValidIntegerEnum::class,
            'ONE',
            1,
            0,
        ];

        yield [
            Data\Integers\ValidIntegerEnum::class,
            'ONE_TWO',
            2,
            1,
        ];

        yield [
            Data\Integers\ValidIntegerEnum::class,
            'ONE_TWO_THREE',
            3,
            2,
        ];

        yield [
            Data\UnsignedIntegers\ValidUnsignedIntegerEnum::class,
            'ONE',
            1,
            0,
        ];

        yield [
            Data\UnsignedIntegers\ValidUnsignedIntegerEnum::class,
            'ONE_TWO',
            2,
            1,
        ];

        yield [
            Data\UnsignedIntegers\ValidUnsignedIntegerEnum::class,
            'ONE_TWO_THREE',
            3,
            2,
        ];

        yield [
            Data\Chars\ValidCharEnum::class,
            'ONE',
            'a',
            0,
        ];

        yield [
            Data\Chars\ValidCharEnum::class,
            'ONE_TWO',
            'b',
            1,
        ];

        yield [
            Data\Chars\ValidCharEnum::class,
            'ONE_TWO_THREE',
            'c',
            2,
        ];
    }

    public static function validMaskEnumNameAndValuePairs(): Generator
    {
        yield [
            Data\Masks\ValidMaskEnum::class,
            'READ',
            1,
            0,
        ];

        yield [
            Data\Masks\ValidMaskEnum::class,
            'WRITE',
            2,
            1,
        ];

        yield [
            Data\Masks\ValidMaskEnum::class,
            'EXECUTE',
            4,
            2,
        ];
    }

    public static function validUndefinedEnumNameAndValuePairs(): Generator
    {
        yield [
            Data\Strings\ValidUndefinedStringEnum::class,
            'ONE',
            'one text',
            0,
        ];

        yield [
            Data\Strings\ValidUndefinedStringEnum::class,
            'ONE_TWO',
            'one two text',
            1,
        ];

        yield [
            Data\Strings\ValidUndefinedStringEnum::class,
            'ONE_TWO_THREE',
            'one two three text',
            2,
        ];

        yield [
            Data\Integers\ValidUndefinedIntegerEnum::class,
            'ONE',
            1,
            0,
        ];

        yield [
            Data\Integers\ValidUndefinedIntegerEnum::class,
            'ONE_TWO',
            2,
            1,
        ];

        yield [
            Data\Integers\ValidUndefinedIntegerEnum::class,
            'ONE_TWO_THREE',
            3,
            2,
        ];

        yield [
            Data\Chars\ValidUndefinedCharEnum::class,
            'ONE',
            'a',
            0,
        ];

        yield [
            Data\Chars\ValidUndefinedCharEnum::class,
            'ONE_TWO',
            'b',
            1,
        ];

        yield [
            Data\Chars\ValidUndefinedCharEnum::class,
            'ONE_TWO_THREE',
            'c',
            2,
        ];
    }

    public static function allValidConstantNameAndValuePairs(): Generator
    {
        yield from self::validConstantNameAndValuePairs();
        yield from self::validMaskEnumNameAndValuePairs();
        yield from self::validUndefinedEnumNameAndValuePairs();
    }

    public static function validStaticFactories(): Generator
    {
        foreach (static::allValidConstantNameAndValuePairs() as $item) {
            yield [
                $item[0],
                Name::resolve($item[1])->camelCase(),
                $item[2],
            ];
        }
    }

    public static function validIsMethods(): Generator
    {
        foreach (static::allValidConstantNameAndValuePairs() as $item) {
            $name = Name::resolve($item[1]);

            yield [
                $item[0],
                $name->upperSnakeCase(),
                'is' . $name->pascalCase(),
                $item[2],
            ];
        }
    }

    public static function allValidValues(): Generator
    {
        $currentName = null;
        $data = [];
        foreach (static::allValidConstantNameAndValuePairs() as $item) {
            if (null === $currentName) {
                $currentName = $item[0];
            }

            if ($currentName === $item[0]) {
                $data[] = $item[2];

                continue;
            }

            yield [
                $currentName,
                $data,
            ];
            $data = [$item[2]];
            $currentName = $item[0];
        }

        yield [
            $currentName,
            $data,
        ];
    }

    public static function allValidNames(): Generator
    {
        $currentName = null;
        $data = [];
        foreach (static::allValidConstantNameAndValuePairs() as $item) {
            if (null === $currentName) {
                $currentName = $item[0];
            }

            if ($currentName === $item[0]) {
                $data[] = new Name($item[1], $item[1]);

                continue;
            }

            yield [
                $currentName,
                $data,
            ];
            $data = [new Name($item[1], $item[1])];
            $currentName = $item[0];
        }

        yield [
            $currentName,
            $data,
        ];
    }

    /**
     * Returns array looking like:
     *  [
     *      '$currentEnumName',
     *      [$name1, $name2...]
     *  ], ...
     */
    public static function allEnumerations(): Generator
    {
        $currentName = null;
        $data = [];
        foreach (static::validConstantNameAndValuePairs() as $item) {
            if (null === $currentName) {
                $currentName = $item[0];
            }

            if ($currentName === $item[0]) {
                $data[] = $item[1];

                continue;
            }

            yield [
                $currentName,
                $data,
            ];
            $data = [$item[1]];
            $currentName = $item[0];
        }

        yield [
            $currentName,
            $data,
        ];
    }

    /**
     * Returns array looking like:
     *  [
     *      $currentEnumName
     *      [$name1, $name2...]
     *      [$excludedName]
     *  ], ...
     */
    public static function allEnumerationsExceptOne(): Generator
    {
        foreach (static::allEnumerations() as $item) {
            $name = array_shift($item);
            $lastItemFromArray = array_pop($item);

            yield [
                $name,
                $item,
                $lastItemFromArray,
            ];
        }
    }

    public static function equals(): Generator
    {
        foreach (static::validConstantNameAndValuePairs() as $item) {
            yield [
                $item[0],
                $item[1],
            ];
        }
    }

    /**
     * Returns array looking like:
     *  [
     *      $enumName
     *      $randomName
     *      $secondRandomName
     *  ], ...
     */
    public static function notEqual(): Generator
    {
        foreach (static::allEnumerations() as $item) {
            shuffle($item[1]);
            $randomFirstItem = array_pop($item[1]);
            shuffle($item[1]);
            $randomSecondItem = array_pop($item[1]);

            yield [
                $item[0],
                $randomFirstItem,
                $randomSecondItem,
            ];
        }
    }

    public static function toString(): Generator
    {
        foreach (static::validConstantNameAndValuePairs() as $item) {
            yield [
                $item[0],
                $item[1]
            ];
        }
    }

    /**
     * Returns array looking like:
     *  [
     *      $enumName
     *      $randomExistingName
     *      [$name, $name...]
     *  ], ...
     */
    public static function isAnyPassing(): Generator
    {
        foreach (static::allEnumerations() as $item) {
            shuffle($item[1]);
            // remove random item from array
            array_pop($item[1]);
            $randomEnumeration = end($item[1]);

            yield [
                $item[0],
                $randomEnumeration,
                $item[1],
            ];
        }
    }

    /**
     * Returns array looking like:
     *  [
     *      $enumName
     *      $randomNotExistingName
     *      [$name, $name...]
     *  ], ...
     */
    public static function isAnyNotPassing(): Generator
    {
        foreach (static::allEnumerations() as $item) {
            shuffle($item[1]);
            $randomEnumeration = array_pop($item[1]);

            yield [
                $item[0],
                $randomEnumeration,
                $item[1],
            ];
        }
    }

    /**
     * Builds serialized string
     */
    public static function serializedEnums(): Generator
    {
        $template = 'O:%d:"%s":1:{s:%d:"\%s\enumerationIndex";i:1;}';

        foreach (self::allValidConstantNameAndValuePairs() as $item){
            $class = $item[0];
            $classCount = strlen($class);
            yield [
                sprintf($template, $classCount, $class, $classCount + 18, $class)
            ];
        }
    }
}
