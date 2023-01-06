<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Unit;

use BadMethodCallException;
use Generator;
use PHPUnit\Framework\TestCase;
use Robier\Enum\UpperSnakeCase;

/**
 * @covers \Robier\Enum\UpperSnakeCase
 */
final class UpperSnakeCaseTest extends TestCase
{
    public function conversionDataProvider(): Generator
    {
        yield 'camelCase' => ['fooBar', 'FOO_BAR'];
        yield 'PascalCase' => ['FooBar', 'FOO_BAR'];
        yield 'UPPER_SNAKE_CASE' => ['FOO_BAR', 'FOO_BAR'];
        yield 'lower_snake_case' => ['foo_bar', 'FOO_BAR'];
        yield 'lower_snake_case multiple underscores' => ['foo__bar', 'FOO_BAR'];
    }

    /**
     * @dataProvider conversionDataProvider()
     */
    public function testConversion(string $test, string $expectation): void
    {
        self::assertSame($expectation, (string)UpperSnakeCase::resolve($test));
    }

    public function testBadVariableHandling(): void
    {
        self::expectException(BadMethodCallException::class);
        self::expectExceptionMessage('.-/ is not a valid variable name');

        UpperSnakeCase::resolve('.-/');
    }
}
