<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Robier\Enum\HasDefault
 */
final class HasDefaultTest extends TestCase
{
    public function dataProviderByIntegerValue(): Generator
    {
        yield 'Provided value can be found' => [
            1,
            HasDefault::CLIENT,
            HasDefault::SUPER_ADMIN,
        ];

        yield 'Provided value can not be found' => [
            999,
            HasDefault::CLIENT,
            HasDefault::CLIENT,
        ];
    }

    public function dataProviderByName(): Generator
    {
        yield 'Existing name lowercase' => [
            'client',
            HasDefault::SUPER_ADMIN,
            HasDefault::CLIENT,
        ];

        yield 'Existing name uppercase' => [
            'CLIENT',
            HasDefault::SUPER_ADMIN,
            HasDefault::CLIENT,
        ];

        yield 'Not existing name uppercase' => [
            'FOO',
            HasDefault::SUPER_ADMIN,
            HasDefault::SUPER_ADMIN,
        ];

        yield 'Not existing name lowercase' => [
            'bar',
            HasDefault::USER,
            HasDefault::USER,
        ];
    }

    /**
     * @dataProvider dataProviderByIntegerValue()
     */
    public function testTryMethod(int $value, HasDefault $default, HasDefault $expected): void
    {
        self::assertSame(
            $expected,
            HasDefault::try($value, $default)
        );
    }

    /**
     * @dataProvider dataProviderByName()
     */
    public function testTryNameMethod(string $name, HasDefault $default, HasDefault $expected): void
    {
        self::assertSame(
            $expected,
            HasDefault::tryName($name, $default)
        );
    }
}
