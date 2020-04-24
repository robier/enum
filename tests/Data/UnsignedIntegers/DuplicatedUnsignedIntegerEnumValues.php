<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\UnsignedIntegers;

/**
 * @method static self new()
 * @method static self oldTest()
 * @method bool isNew()
 * @method bool isOldTest()
 */
final class DuplicatedUnsignedIntegerEnumValues
{
    use \Robier\Enum\UnsignedIntegerEnum;

    protected const NEW = 3;
    protected const OLD_TEST = 3;
}
