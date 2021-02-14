<?php

declare(strict_types = 1);

namespace Robier\Enum;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * @internal
 */
final class Collection implements IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @var IntegerEnum[]|UnsignedIntegerEnum[]|StringEnum[]|MaskEnum[]
     */
    private $enums;

    public function __construct(object ...$enums)
    {
        foreach($enums as $paramNumber => $enum) {
            $this->offsetSet($paramNumber, $enum);
        }
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->enums);
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->enums);
    }

    public function offsetGet($offset): ?object
    {
        return $this->enums[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (!is_object($value)) {
            throw Exception\Collection::notObject($value);
        }

        if (!isEnum($value)) {
            throw Exception\Collection::objectNotEnum($value);
        }

        $this->enums[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->enums[$offset]);
    }

    public function plainNames(): array
    {
        return array_map(static function (Name $name): string {
            return (string)$name;
        }, $this->names());
    }

    public function names(): array
    {
        $names = [];
        foreach ($this->enums as $enum) {
            if (!isMaskEnum($enum)) {
                $names[] = $enum->name();
                continue;
            }

            foreach ($enum->names() as $name) {
                $names[] = $name;
            }
        }

        return $names;
    }

    public function values(): array
    {
        return array_map(static function(object $enum) {
            return $enum->value();
        }, $this->enums);
    }

    public function count(): int
    {
        return count($this->enums);
    }

    public function toArray(): array
    {
        return $this->enums;
    }

    public function any(object ...$enums): bool
    {
        foreach($enums as $enum) {
            foreach($this->enums as $collectionEnum) {
                if($enum instanceof $collectionEnum && $enum->equal($collectionEnum)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function all(object ...$enums): bool
    {
        foreach($enums as $enum) {
            foreach($this->enums as $collectionEnum) {
                if(!$enum instanceof $collectionEnum && !$enum->equal($collectionEnum)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function filterByCallback(callable $callback): self
    {
        return new self(...array_filter($this->enums, $callback));
    }

    public function filterByEnumClass(string $enum, string ...$enums): self
    {
        array_unshift($enums, $enum);

        // validate
        foreach ($enums as $enum) {
            if (!isEnum($enum)) {
                throw Exception\Collection::classNotEnum($enum);
            }
        }

        return $this->filterByCallback(static function(object $enum) use ($enums): bool {
            return in_array(get_class($enum), $enums);
        });
    }
}
