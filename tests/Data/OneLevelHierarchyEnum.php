<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Data;

use Robier\Enum\StringEnum;

/**
 * @internal
 */
trait OneLevelChild
{
    use StringEnum;
}

final class OneLevelHierarchyEnum
{
    use OneLevelChild;

    private const TEST = 'test';
}
