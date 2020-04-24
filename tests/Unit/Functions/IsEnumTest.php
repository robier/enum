<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Unit\Functions;

use PHPUnit\Framework\TestCase;
use function Robier\Enum\isEnum;

/**
 * @covers \Robier\Enum\isEnum
 */
final class IsEnumTest extends TestCase
{
    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::charEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::integerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::unsignedIntegerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::maskEnum()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringHierarchicalEnums()
     */
    public function testItWillReturnTrueIfEnumProvided($class): void
    {
        $this->assertTrue(isEnum($class));
    }

    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::invalidEnums()
     */
    public function testItWillReturnFalseIfEnumIsNotProvided($class): void
    {
        $this->assertFalse(isEnum($class));
    }
}
