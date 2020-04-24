<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Masks;

/**
 * @method static self test()
 * @method bool isTest()
 */
final class BadTypeMaskEnumValues
{
    use \Robier\Enum\MaskEnum;

    protected const TEST = 'foo bar';
}
