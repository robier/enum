<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use Robier\Enum\Exception;

/**
 * @covers \Robier\Enum\Exception
 */
final class ExceptionTest extends TestCase
{
    public function testAllExcludedMessage(): void
    {
        self::assertSame('All possible values are excluded', Exception::allExcluded()->getMessage());
    }
}
