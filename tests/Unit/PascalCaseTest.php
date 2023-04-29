<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use Generator;
use Robier\Enum\PascalCase;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Robier\Enum\PascalCase
 */
final class PascalCaseTest extends TestCase
{
    public function conversionDataProvider(): Generator
    {
        yield 'camelCase' => ['fooBar', 'FooBar'];
        yield 'PascalCase' => ['FooBar', 'FooBar'];
        yield 'UPPER_SNAKE_CASE' => ['FOO_BAR', 'FooBar'];
        yield 'lower_snake_case' => ['foo_bar', 'FooBar'];
    }

    /**
     * @dataProvider conversionDataProvider()
     */
    public function testConversion(string $test, string $expectation): void
    {
        self::assertSame($expectation, (string)PascalCase::resolve($test));
    }
}
