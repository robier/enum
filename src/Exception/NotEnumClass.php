<?php

declare(strict_types = 1);

namespace Robier\Enum\Exception;

use Exception;
use Throwable;

final class NotEnumClass extends Exception
{
    /**
     * @var string
     */
    private $class;

    private function __construct(string $class, Throwable $previous = null)
    {
        $this->class = $class;

        $message = sprintf('Provided class %s is not a enum.', $class);

        parent::__construct($message, 0, $previous);
    }

    /**
     * @param string|object $class
     */
    public static function new($class, Throwable $previous = null): self
    {
        $className = $class;
        if (is_object($class)) {
            $className = get_class($class);
        }

        return new static($className, $previous);
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
