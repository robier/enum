<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Masks;

/**
 * @method static self new()
 * @method static self oldTest()
 * @method bool isNew()
 * @method bool isOldTest()
 */
final class DuplicatedMaskEnumValues
{
    use \Robier\Enum\MaskEnum;

    protected const NEW = 3;
    protected const OLD_TEST = 3;
}
