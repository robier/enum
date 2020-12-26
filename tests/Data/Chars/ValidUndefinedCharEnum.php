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
final class ValidUndefinedCharEnum
{
    use \Robier\Enum\CharEnum;
    use \Robier\Enum\Feature\Undefined;

    protected const ONE = 'a';
    protected const ONE_TWO = 'b';
    protected const ONE_TWO_THREE = 'c';
}
