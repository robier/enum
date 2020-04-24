<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Masks;

/**
 * @method static self read()
 * @method static self write()
 * @method static self execute()
 * @method bool isRead()
 * @method bool isWrite()
 * @method bool isExecute()
 */
final class ValidMaskEnum
{
    use \Robier\Enum\MaskEnum;

    protected const READ = 1;
    protected const WRITE = 2;
    protected const EXECUTE = 4;
}
