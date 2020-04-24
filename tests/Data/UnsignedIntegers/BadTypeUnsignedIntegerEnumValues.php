<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\UnsignedIntegers;

/**
 * @method static self test()
 * @method bool isTest()
 */
final class BadTypeUnsignedIntegerEnumValues
{
    use \Robier\Enum\UnsignedIntegerEnum;

    protected const TEST = 'foo bar';
}
