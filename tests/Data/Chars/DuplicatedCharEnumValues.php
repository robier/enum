<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Data\Chars;

final class DuplicatedCharEnumValues
{
    use \Robier\Enum\CharEnum;

    protected const NEW = 'a';
    protected const OLD_TEST = 'a';
}
