<?php

declare(strict_types = 1);

namespace Robier\Enum\Feature;

use function Robier\Enum\isMaskEnum;
/**
 * If you try to create an Enum you will get "empty" one, without any defined value instead of exception.
 * Useful if you use getters that can be some value or nothing (like doctrine getters).
 */
trait Undefined
{
    public static function undefined(): self
    {
        static::setup();

        return static::$enumeration['data'][self::class]['enum'];
    }

    public function isUndefined(): bool
    {
        if (isMaskEnum($this)) {
            return $this->enumerationValue === null;
        }

        return $this->enumerationIndex === -1;
    }

    public function notUndefined(): bool
    {
        return !$this->isUndefined();
    }
}
