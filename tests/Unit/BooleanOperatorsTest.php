<?php

declare(strict_types=1);

namespace Robier\Enum\Test\Unit;

use BadMethodCallException;
use Generator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Robier\Enum\BooleanOperators
 */
final class BooleanOperatorsTest extends TestCase
{
    public function dataProvider(): Generator
    {
        yield 'Super admin' => [
            TestEnum::SUPER_ADMIN,
            true,
            false,
            false,
            false,
        ];

        yield 'Admin' => [
            TestEnum::ADMIN,
            false,
            true,
            false,
            false,
        ];

        yield 'User' => [
            TestEnum::USER,
            false,
            false,
            true,
            false,
        ];

        yield 'Client' => [
            TestEnum::CLIENT,
            false,
            false,
            false,
            true,
        ];
    }

    /**
     * @dataProvider dataProvider()
     */
    public function testBooleanMethodIs(TestEnum $enum, bool $isSuperAdmin, bool $isAdmin, bool $isUser, bool $isClient): void
    {
        if ($isSuperAdmin) {
            self::assertTrue($enum->isSuperAdmin());
        } else {
            self::assertNotTrue($enum->isSuperAdmin());
        }

        if ($isAdmin) {
            self::assertTrue($enum->isAdmin());
        } else {
            self::assertNotTrue($enum->isAdmin());
        }

        if ($isUser) {
            self::assertTrue($enum->isUser());
        } else {
            self::assertNotTrue($enum->isUser());
        }

        if ($isClient) {
            self::assertTrue($enum->isClient());
        } else {
            self::assertNotTrue($enum->isClient());
        }
    }

    /**
     * @dataProvider dataProvider()
     */
    public function testBooleanMethodNot(TestEnum $enum, bool $isSuperAdmin, bool $isAdmin, bool $isUser, bool $isClient): void
    {
        if ($isSuperAdmin) {
            self::assertFalse($enum->notSuperAdmin());
        } else {
            self::assertNotFalse($enum->notSuperAdmin());
        }

        if ($isAdmin) {
            self::assertFalse($enum->notAdmin());
        } else {
            self::assertNotFalse($enum->notAdmin());
        }

        if ($isUser) {
            self::assertFalse($enum->notUser());
        } else {
            self::assertNotFalse($enum->notUser());
        }

        if ($isClient) {
            self::assertFalse($enum->notClient());
        } else {
            self::assertNotFalse($enum->notClient());
        }
    }

    public function testCallingNotDefinedIsFunctionWillThrowException(): void
    {
        self::expectException(BadMethodCallException::class);
        self::expectExceptionMessage('Not defined case in enum with name TEST');

        TestEnum::CLIENT->isTest();
    }

    public function testCallingNotDefinedNotFunctionWillThrowException(): void
    {
        self::expectException(BadMethodCallException::class);
        self::expectExceptionMessage('Not defined case in enum with name TEST');

        TestEnum::CLIENT->notTest();
    }

    public function testCallingNotHandledMagicMethod(): void
    {
        self::expectException(BadMethodCallException::class);
        self::expectExceptionMessage('Call to undefined function testingSomething()');

        TestEnum::CLIENT->testingSomething();
    }

    public function anyDataProvider(): Generator
    {
        yield 'not matching super admin with 2 other types' => [
            TestEnum::SUPER_ADMIN,
            [TestEnum::USER, TestEnum::CLIENT],
            false,
        ];

        yield 'not matching super admin with 3 other types' => [
            TestEnum::SUPER_ADMIN,
            [TestEnum::USER, TestEnum::CLIENT, TestEnum::CLIENT],
            false,
        ];

        yield 'matching super admin' => [
            TestEnum::SUPER_ADMIN,
            [TestEnum::USER, TestEnum::CLIENT, TestEnum::CLIENT, TestEnum::SUPER_ADMIN],
            true,
        ];

    }

    /**
     * @dataProvider anyDataProvider()
     */
    public function testAnyMethod(TestEnum $enum, array $enums, bool $expected): void
    {
        self::assertSame($expected, $enum->any(...$enums));
    }

    public function testRandomMethod(): void
    {
        self::assertInstanceOf(TestEnum::class, TestEnum::random());
    }

    public function testRandomMethodExceptAll(): void
    {
        self::expectException(BadMethodCallException::class);
        self::expectExceptionMessage('All possible values are excluded');

        TestEnum::random(...TestEnum::cases());
    }
}
