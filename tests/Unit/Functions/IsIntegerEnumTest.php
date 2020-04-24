<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Unit\Functions;

use PHPUnit\Framework\TestCase;
use function Robier\Enum\isIntegerEnum;

/**
 * @covers \Robier\Enum\isIntegerEnum
 */
final class IsIntegerEnumTest extends TestCase
{
    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::integerEnums()
     */
    public function testItWillReturnTrueIfIntegerEnumProvided($class): void
    {
        $this->assertTrue(isIntegerEnum($class));
    }

    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::unsignedIntegerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::charEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::maskEnum()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::invalidEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringHierarchicalEnums()
     */
    public function testItWillReturnFalseIfProvidedEnumIsNotIntegerTypeOrItsNotEnumAtAll($class): void
    {
        $this->assertFalse(isIntegerEnum($class));
    }
}
