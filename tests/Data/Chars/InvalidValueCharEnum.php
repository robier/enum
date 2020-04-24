<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Chars;

/**
 * @method static self one()
 * @method static self oneTwo()
 * @method static self oneTwoThree()
 * @method bool isOne()
 * @method bool isOneTwo()
 * @method bool isOneTwoThree()
 */
final class InvalidValueCharEnum
{
    use \Robier\Enum\CharEnum;

    protected const ONE = 'abc';
    protected const ONE_TWO = 'bcd';
    protected const ONE_TWO_THREE = 'cde';
}
