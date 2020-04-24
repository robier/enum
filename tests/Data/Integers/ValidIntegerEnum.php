<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Integers;

/**
 * @method static self one()
 * @method static self oneTwo()
 * @method static self oneTwoThree()
 * @method bool isOne()
 * @method bool isOneTwo()
 * @method bool isOneTwoThree()
 */
final class ValidIntegerEnum
{
    use \Robier\Enum\IntegerEnum;

    protected const ONE = 1;
    protected const ONE_TWO = 2;
    protected const ONE_TWO_THREE = 3;
}
