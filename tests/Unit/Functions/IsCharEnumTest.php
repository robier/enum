<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Unit\Functions;

use PHPUnit\Framework\TestCase;
use function Robier\Enum\isCharEnum;

/**
 * @covers \Robier\Enum\isCharEnum
 */
final class IsCharEnumTest extends TestCase
{
    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::charEnums()
     */
    public function testItWillReturnTrueIfCharEnumProvided($class): void
    {
        $this->assertTrue(isCharEnum($class));
    }

    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::integerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::unsignedIntegerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::maskEnum()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::invalidEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringHierarchicalEnums()
     */
    public function testItWillReturnFalseIfProvidedEnumIsNotStringTypeOrItsNotEnumAtAll($class): void
    {
        $this->assertFalse(isCharEnum($class));
    }
}
