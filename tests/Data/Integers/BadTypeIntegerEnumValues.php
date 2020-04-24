<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Integers;

/**
 * @method static self test()
 * @method bool isTest()
 */
final class BadTypeIntegerEnumValues
{
    use \Robier\Enum\IntegerEnum;

    protected const TEST = 'foo bar';
}
