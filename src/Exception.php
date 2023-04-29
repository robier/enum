<?php

declare(strict_types = 1);

namespace Robier\Enum;

final class Exception extends \Exception
{
    public static function allExcluded(): self
    {
        return new self('All possible values are excluded');
    }
}
