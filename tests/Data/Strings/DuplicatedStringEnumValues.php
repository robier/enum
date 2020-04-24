<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Data\Strings;

final class DuplicatedStringEnumValues
{
    use \Robier\Enum\StringEnum;

    private const ONE = 'duplicated test';
    private const ONE_TWO = 'duplicated test';
}
