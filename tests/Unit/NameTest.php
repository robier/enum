<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use Exception;
use Generator;
use Robier\Enum\Name;
use PHPUnit\Framework\TestCase;
use Robier\Enum\Test\Data\StringCaseType;

/**
 * @covers \Robier\Enum\Name
 * @runTestsInSeparateProcesses
 */
class NameTest extends TestCase
{
    /**
     * @dataProvider conversionDataProvider
     */
    public function testCaseResolving(string $caseName, string $type): void
    {
        $name = Name::resolve($type);

        $this->assertSame($caseName, $name->upperSnakeCase());
    }

    /**
     * @dataProvider conversionDataProvider
     */
    public function testGettingOriginal(string $caseName, string $type): void
    {
        $name = Name::resolve($type);

        $this->assertSame($type, $name->original());
    }

    /**
     * @dataProvider comparisionDataProvider
     */
    public function testComparingNameWithObject(string $first, string $second, bool $isSame): void
    {
        $first = Name::resolve($first);
        $second = Name::resolve($second);

        if ($isSame) {
            $this->assertTrue($first->isEqual($second));
        } else {
            $this->assertFalse($first->isEqual($second));
        }
    }

    /**
     * @dataProvider comparisionDataProvider
     */
    public function testComparingNameWithString(string $first, string $second, bool $isSame): void
    {
        $first = Name::resolve($first);

        if ($isSame) {
            $this->assertTrue($first->isSame($second));
        } else {
            $this->assertFalse($first->isSame($second));
        }
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testConvertingToString(Name $name, string $upperSnakeCase): void
    {
        $this->assertSame($upperSnakeCase, $name->__toString());
    }

    /**
     * @dataProvider validDataProvider
     */
    public function testConvertingMethods(
        Name $name,
        string $upperSnakeCase,
        string $lowerSnakeCase,
        string $camelCase,
        string $pascalCase,
        string $upperKebabCase,
        string $lowerKebabCase,
        string $upperSpaceCase,
        string $lowerSpaceCase
    ): void
    {
        $this->assertSame($upperSnakeCase, $name->upperSnakeCase());
        $this->assertSame($lowerSnakeCase, $name->lowerSnakeCase());
        $this->assertSame($camelCase, $name->camelCase());
        $this->assertSame($pascalCase, $name->pascalCase());
        $this->assertSame($upperKebabCase, $name->upperKebabCase());
        $this->assertSame($lowerKebabCase, $name->lowerKebabCase());
        $this->assertSame($upperSpaceCase, $name->upperSpaceCase());
        $this->assertSame($lowerSpaceCase, $name->lowerSpaceCase());
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalidNameResolving(string $invalidName): void
    {
        $this->expectException(Exception::class);
        Name::resolve($invalidName);
    }

    public function conversionDataProvider(): Generator
    {
        /** @var StringCaseType $type */
        foreach (StringCaseType::all() as $type) {
            // add _CASE to name
            $name = $type->name()->upperSnakeCase() . '_CASE';

            yield $name => [$name, $type->value()];
        }

        yield 'multiple spaces' => ['TEST_CASE', 'test  case'];
        yield 'multiple minuses' => ['TEST_CASE', 'test---case'];
        yield 'multiple underscores' => ['TEST_CASE', 'test____case'];
    }

    public function comparisionDataProvider(): Generator
    {
        yield ['TEST', 'test', true];
        yield ['TEST_CASE', 'test-case', true];
        yield ['TEST_CASE', 'testCase', true];
        yield ['TEST_CASE', 'test_Case', true];
        yield ['foo bar', 'FooBar', true];
        yield ['foo bar', 'fooBar', true];
        yield ['foo bar', 'foo_Bar', true];
        yield ['foo bar', 'BarFoo', false];
        yield ['foo bar', 'Bar_Foo', false];
    }

    public function validDataProvider(): Generator
    {
        yield 'one word name' => [
            new Name('TEST', 'TEST'),
            'TEST', // UPPER_SNAKE_CASE
            'test', // lower_snake_case
            'test', // camelCase
            'Test', // PascalCase
            'TEST', // UPPER-KEBAB-CASE
            'test', // lower-kebab-case
            'TEST', // UPPER SPACE CASE
            'test', // lower space case
        ];

        yield 'multiple words name' => [
            new Name('TEST_FOO_BAR', 'TEST_FOO_BAR'),
            'TEST_FOO_BAR', // UPPER_SNAKE_CASE
            'test_foo_bar', // lower_snake_case
            'testFooBar', // camelCase
            'TestFooBar', // PascalCase
            'TEST-FOO-BAR', // UPPER-KEBAB-CASE
            'test-foo-bar', // lower-kebab-case
            'TEST FOO BAR', // UPPER SPACE CASE
            'test foo bar', // lower space case
        ];

        yield 'multiple words name with numbers' => [
            new Name('TEST_FOO_123_BAR', 'TEST_FOO_123_BAR'),
            'TEST_FOO_123_BAR', // UPPER_SNAKE_CASE
            'test_foo_123_bar', // lower_snake_case
            'testFoo123Bar', // camelCase
            'TestFoo123Bar', // PascalCase
            'TEST-FOO-123-BAR', // UPPER-KEBAB-CASE
            'test-foo-123-bar', // lower-kebab-case
            'TEST FOO 123 BAR', // UPPER SPACE CASE
            'test foo 123 bar', // lower space case
        ];
    }

    public function invalidDataProvider(): Generator
    {
        yield [
            'data5$#',
            '12421'
        ];
    }
}
