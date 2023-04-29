<?php

declare(strict_types = 1);

namespace Robier\Enum;

trait HasRandom
{
    /**
     * @throws Exception
     */
    public static function random(self ...$except): self
    {
        $array = self::cases();

        foreach ($except as $item) {
            $key = array_search($item, $array);
            unset($array[$key]);
        }

        if (empty($array)) {
            throw Exception::allExcluded();
        }

        $index = array_rand($array);

        return $array[$index];
    }
}
