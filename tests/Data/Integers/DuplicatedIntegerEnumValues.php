<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Integers;

/**
 * @method static self new()
 * @method static self oldTest()
 * @method bool isNew()
 * @method bool isOldTest()
 */
final class DuplicatedIntegerEnumValues
{
    use \Robier\Enum\IntegerEnum;

    protected const NEW = 3;
    protected const OLD_TEST = 3;
}
