<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Unit\Functions;

use PHPUnit\Framework\TestCase;
use function Robier\Enum\isStringEnum;

/**
 * @covers \Robier\Enum\isStringEnum
 */
final class IsStringEnumTest extends TestCase
{
    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringHierarchicalEnums()
     */
    public function testItWillReturnTrueIfStringEnumProvided($class): void
    {
        $this->assertTrue(isStringEnum($class));
    }

    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::charEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::integerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::unsignedIntegerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::maskEnum()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::invalidEnums()
     */
    public function testItWillReturnFalseIfProvidedEnumIsNotStringTypeOrItsNotEnumAtAll($class): void
    {
        $this->assertFalse(isStringEnum($class));
    }
}
