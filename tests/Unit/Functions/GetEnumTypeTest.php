<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit\Functions;

use PHPUnit\Framework\TestCase;
use Robier\Enum\Exception\NotEnumClass;
use function Robier\Enum\getEnumType;

/**
 * @covers \Robier\Enum\getEnumType
 */
final class GetEnumTypeTest extends TestCase
{
    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::getEnumType()
     * @throws NotEnumClass
     */
    public function testItWillReturnExpectedTypeWhenValidEnumProvided(string $type, $class): void
    {
        $this->assertSame(
            $type,
            getEnumType($class)
        );
    }

    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::invalidEnums()
     * @throws \Robier\Enum\Exception\NotEnumClass
     */
    public function testItWillReturnNullWhenInvalidEnumProvided($class): void
    {
        $this->expectException(NotEnumClass::class);
        $this->expectExceptionMessage(NotEnumClass::new($class)->getMessage());

        getEnumType($class);
    }
}
