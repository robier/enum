<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Data;

use Robier\Enum\StringEnum;

/**
 * @internal
 */
trait MultipleLevelsChild
{
    use StringEnum;
}

/**
 * @internal
 */
trait MultipleLevelsChild2
{
    use MultipleLevelsChild;
}

/**
 * @internal
 */
trait MultipleLevelsChild3
{
    use MultipleLevelsChild2;
}

/**
 * @internal
 */
trait MultipleLevelsChild4
{
    use MultipleLevelsChild3;
}

final class MultipleLevelsHierarchyEnum
{
    use MultipleLevelsChild4;

    private const TEST = 'test';
}
