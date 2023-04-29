<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use PHPUnit\Framework\TestCase;
use Robier\Enum\Exception;

/**
 * @covers \Robier\Enum\HasRandom
 */
final class HasRandomTest extends TestCase
{
    public function testRandomMethod(): void
    {
        self::assertInstanceOf(HasRandom::class, HasRandom::random());
    }

    public function testRandomMethodExceptAll(): void
    {
        self::expectException(Exception::class);
        self::expectExceptionMessage(Exception::allExcluded()->getMessage());

        HasRandom::random(...HasRandom::cases());
    }
}
