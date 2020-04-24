<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Unit\Functions;

use PHPUnit\Framework\TestCase;
use function Robier\Enum\isMaskEnum;

/**
 * @covers \Robier\Enum\isMaskEnum
 */
final class IsMaskEnumTest extends TestCase
{
    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::maskEnum()
     */
    public function testItWillReturnTrueIfMaskEnumProvided($class): void
    {
        $this->assertTrue(isMaskEnum($class));
    }

    /**
     * @var string|object $class
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::charEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::integerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::unsignedIntegerEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::invalidEnums()
     * @dataProvider \Robier\Enum\Test\Unit\Functions\DataProvider::stringHierarchicalEnums()
     */
    public function testItWillReturnFalseIfProvidedEnumIsNotMaskTypeOrItsNotEnumAtAll($class): void
    {
        $this->assertFalse(isMaskEnum($class));
    }
}
