<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Strings;

/**
 * @method static self one()
 * @method static self oneTwo()
 * @method static self oneTwoThree()
 * @method bool isOne()
 * @method bool isOneTwo()
 * @method bool isOneTwoThree()
 */
final class ValidUndefinedStringEnum
{
    use \Robier\Enum\StringEnum;
    use \Robier\Enum\Feature\Undefined;

    protected const ONE = 'one text';
    protected const ONE_TWO = 'one two text';
    protected const ONE_TWO_THREE = 'one two three text';
}
