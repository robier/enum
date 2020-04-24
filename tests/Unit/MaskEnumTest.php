<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use PHPUnit\Framework\TestCase;
use Robier\Enum\Test\Data\Masks\ValidMaskEnum;

/**
 * @covers \Robier\Enum\MaskEnum
 * @runTestsInSeparateProcesses
 */
class MaskEnumTest extends TestCase
{
    public function testItWillCreateMaskEnumerationWhenValuesProvided(): void
    {
        $validEnum = ValidMaskEnum::byValue(1, 2);

        $this->assertInstanceOf(ValidMaskEnum::class, $validEnum);

        $this->assertTrue($validEnum->isRead());
        $this->assertTrue($validEnum->isWrite());
        $this->assertFalse($validEnum->isExecute());
        $this->assertSame(3, $validEnum->value());
    }

    public function testItWillCreateMaskEnumerationWhenNamesProvided(): void
    {
        $validEnum = ValidMaskEnum::byName('read', 'write');

        $this->assertInstanceOf(ValidMaskEnum::class, $validEnum);

        $this->assertTrue($validEnum->isRead());
        $this->assertTrue($validEnum->isWrite());
        $this->assertFalse($validEnum->isExecute());
        $this->assertSame(3, $validEnum->value());
    }

    public function testItWillCreateMaskEnumerationWhenIndicesProvided(): void
    {
        $validEnum = ValidMaskEnum::byIndex(0, 1);

        $this->assertInstanceOf(ValidMaskEnum::class, $validEnum);

        $this->assertTrue($validEnum->isRead());
        $this->assertTrue($validEnum->isWrite());
        $this->assertFalse($validEnum->isExecute());
        $this->assertSame(3, $validEnum->value());
        $this->assertFalse($validEnum->containsAll(ValidMaskEnum::read(), ValidMaskEnum::write(), ValidMaskEnum::execute()));
    }

    public function testItWillCreateMaskEnumerationWhenRandomMethodCalled(): void
    {
        $validEnum = ValidMaskEnum::getRandom();

        $this->assertInstanceOf(ValidMaskEnum::class, $validEnum);

        $this->assertTrue($validEnum->any(ValidMaskEnum::read(), ValidMaskEnum::write(), ValidMaskEnum::execute()));
    }

    public function testItWillCreateMaskEnumerationWhenRandomMethodCalledExceptValue(): void
    {
        $validEnum = ValidMaskEnum::getRandom(ValidMaskEnum::read());

        $this->assertInstanceOf(ValidMaskEnum::class, $validEnum);

        $this->assertTrue($validEnum->any(ValidMaskEnum::read(), ValidMaskEnum::write(), ValidMaskEnum::execute()));
    }

    public function testItWillCreateMaskEnumerationContainingAllValues(): void
    {
        $validEnum = ValidMaskEnum::allInOne();

        $this->assertInstanceOf(ValidMaskEnum::class, $validEnum);

        $this->assertTrue($validEnum->any(ValidMaskEnum::read(), ValidMaskEnum::write(), ValidMaskEnum::execute()));
        $this->assertTrue($validEnum->containsAll(ValidMaskEnum::read(), ValidMaskEnum::write(), ValidMaskEnum::execute()));
    }

    public function testItWillCreateMaskEnumerationContainingAllValuesExceptSome(): void
    {
        $validEnum = ValidMaskEnum::allInOne(ValidMaskEnum::read());

        $this->assertInstanceOf(ValidMaskEnum::class, $validEnum);

        $this->assertTrue($validEnum->any(ValidMaskEnum::read(), ValidMaskEnum::write(), ValidMaskEnum::execute()));
        $this->assertFalse($validEnum->containsAll(ValidMaskEnum::read(), ValidMaskEnum::write(), ValidMaskEnum::execute()));

        $this->assertSame(6, $validEnum->value());
    }

    public function testItWillReturnFalseIfMaskEnumerationDoesNotContainAnyValue(): void
    {
        $validEnum = ValidMaskEnum::execute();

        $this->assertFalse($validEnum->any(ValidMaskEnum::read(), ValidMaskEnum::write()));
    }

    public function testItWillNotFindAnyMatchingMaskEnumeration(): void
    {
        $validEnum = ValidMaskEnum::execute();

        $this->assertFalse($validEnum->any(ValidMaskEnum::read(), ValidMaskEnum::write()));
    }

    public function testTwoMaskEnumerationsAreEqual(): void
    {
        $validEnum = ValidMaskEnum::execute();

        $this->assertTrue($validEnum->equal(ValidMaskEnum::execute()));
    }

    public function testTwoMaskEnumerationsAreNotEqual(): void
    {
        $validEnum = ValidMaskEnum::execute();

        $this->assertFalse($validEnum->equal(ValidMaskEnum::read()));
    }

    public function testEnumerationProvidesAllNames(): void
    {
        $allNames = ValidMaskEnum::getNames();

        $this->assertCount(3, $allNames);
    }

    public function testEnumerationProvidesAllValues(): void
    {
        $allValues = ValidMaskEnum::getValues();

        $this->assertCount(3, $allValues);
    }
}
