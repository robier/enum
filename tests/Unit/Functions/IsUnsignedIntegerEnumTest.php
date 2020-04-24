<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Unit\Functions;

use PHPUnit\Framework\TestCase;
use function Robier\Enum\isUnsignedIntegerEnum;

/**
 * @covers \Robier\Enum\isUnsignedIntegerEnum
 */
final class IsUnsignedIntegerEnumTest extends TestCase
{
    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::unsignedIntegerEnums()
     */
    public function testItWillReturnTrueIfIntegerEnumProvided($class): void
    {
        $this->assertTrue(isUnsignedIntegerEnum($class));
    }

    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::integerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::charEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::maskEnum()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::invalidEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringHierarchicalEnums()
     */
    public function testItWillReturnFalseIfProvidedEnumIsNotIntegerTypeOrItsNotEnumAtAll($class): void
    {
        $this->assertFalse(isUnsignedIntegerEnum($class));
    }
}
