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

        return static::$enumeration['undefined']['enum'];
    }

    public function isUndefined(): bool
    {
        if (isMaskEnum($this)) {
            return static::$enumeration['undefined'] && $this->enumerationValue === null;
        }

        return static::$enumeration['undefined'] && $this->enumerationIndex === -1;
    }

    public function notUndefined(): bool
    {
        return !$this->isUndefined();
    }
}
