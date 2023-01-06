<?php

declare(strict_types = 1);

namespace Robier\Enum;

use BadMethodCallException;

trait BooleanOperators
{
    public function __call($methodName, $arguments)
    {
        $match = [
            'is' => 2,
            'not' => 3
        ];
        $type = null;

        foreach ($match as $key => $numberOfChars) {
            if (str_starts_with($methodName, $key)) {
                $type = $key;
                break;
            }
        }

        if ($type === null) {
            throw new BadMethodCallException("Call to undefined function $methodName()");
        }

        $name = UpperSnakeCase::resolve(substr($methodName, $match[$type]));

        $constantName = "self::$name";

        if (!defined($constantName)) {
            throw new BadMethodCallException("Not defined case in enum with name $name");
        }

        $guessedEnum = constant($constantName);

        return match ($type) {
            'is' => $this === $guessedEnum,
            'not' => $this !== $guessedEnum,
        };
    }

    public function any(self ...$enums): bool
    {
        return in_array($this, $enums, true);
    }

    public static function random(self ...$except): self
    {
        $array = self::cases();

        foreach ($except as $item) {
            $key = array_search($item, $array);
            unset($array[$key]);
        }

        if (empty($array)) {
            throw new BadMethodCallException('All possible values are excluded');
        }

        $index = array_rand($array);

        return $array[$index];
    }
}
