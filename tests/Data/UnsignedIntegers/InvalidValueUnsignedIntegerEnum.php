<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\UnsignedIntegers;

/**
 * @method static self one()
 * @method static self oneTwo()
 * @method static self oneTwoThree()
 * @method bool isOne()
 * @method bool isOneTwo()
 * @method bool isOneTwoThree()
 */
final class InvalidValueUnsignedIntegerEnum
{
    use \Robier\Enum\UnsignedIntegerEnum;

    protected const ONE = -1;
}
