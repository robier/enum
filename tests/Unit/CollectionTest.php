<?php

declare(strict_types = 1);

namespace Robier\Enum\Test\Unit;

use Generator;
use PHPUnit\Framework\TestCase;
use Robier\Enum\Collection;
use stdClass;
use function Robier\Enum\isMaskEnum;

/**
 * @covers \Robier\Enum\Collection
 * @runTestsInSeparateProcesses
 */
class CollectionTest extends TestCase
{
    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsWithAllNames()
     */
    public function testCountingAllItemsInCollection(string $enum, array $names): void
    {
        $all = call_user_func([$enum, 'all']);

        $collection = new Collection(...$all);

        $this->assertCount(count($names), $collection);
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsWithAllValues()
     */
    public function testValuesAreTheSame(string $enum, $values): void
    {
        $all = call_user_func([$enum, 'all']);

        $collection = new Collection(...$all);

        $collectionValues = $collection->values();

        foreach($collectionValues as $position => $value) {
            $this->assertSame($values[$position], $value);
        }
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsWithAllNames()
     */
    public function testNamesAreTheSame(string $enum, array $names): void
    {
        $all = call_user_func([$enum, 'all']);

        $collection = new Collection(...$all);

        $collectionNames = $collection->names();

        /** @var \Robier\Enum\Name $name */
        foreach($collectionNames as $position => $name) {
            $this->assertTrue($name->isSame($names[$position]));
        }
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsWithAllNames()
     */
    public function testArrayAccessWorking(string $enum, array $names): void
    {
        $all = call_user_func([$enum, 'all']);

        $collection = new Collection(...$all);

        if (isMaskEnum($enum)) {
            $this->assertSame($names[0], (string)$collection[0]->names()[0]);
            return;
        }

        $this->assertSame($names[0], (string)$collection[0]->name());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsWithAllNames()
     */
    public function testToArray(string $enum, array $names): void
    {
        $all = call_user_func([$enum, 'all']);

        $collection = new Collection(...$all);

        $this->assertCount(count($names), $collection->toArray());
        $this->assertIsArray($collection->toArray());
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsWithAllNames()
     */
    public function testIsSet(string $enum): void
    {
        $all = call_user_func([$enum, 'all']);

        $collection = new Collection(...$all);

        $this->assertTrue(isset($collection[0]));
        $this->assertFalse(isset($collection[99999]));
    }

    public function badDataProvider(): Generator
    {
        yield 'not object provided - number' => [
            1,
            \Robier\Enum\Exception\Collection::class,
            \Robier\Enum\Exception\Collection::notObject()->getMessage(),
        ];

        yield 'not object provided - string' => [
            'test',
            \Robier\Enum\Exception\Collection::class,
            \Robier\Enum\Exception\Collection::notObject()->getMessage(),
        ];

        yield 'not object provided - null' => [
            null,
            \Robier\Enum\Exception\Collection::class,
            \Robier\Enum\Exception\Collection::notObject()->getMessage(),
        ];

        yield 'stdClass provided' => [
            new stdClass(),
            \Robier\Enum\Exception\Collection::class,
            \Robier\Enum\Exception\Collection::objectNotEnum(new stdClass())->getMessage(),
        ];
    }

    /**
     * @dataProvider badDataProvider()
     */
    public function testWithBadData($payload, string $exception, string $exceptionMessage): void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage($exceptionMessage);

        $collection = new Collection();
        $collection[] = $payload;
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsWithAllNames()
     */
    public function testItemsCanBeTransversed(string $enum, array $names): void
    {
        $all = call_user_func([$enum, 'all']);

        $collection = new Collection(...$all);

        foreach($collection as $position => $item) {
            if(isMaskEnum($enum)) {
                $this->assertTrue($item->names()[0]->isSame($names[$position]));
                continue;
            }

            $this->assertTrue($item->name()->isSame($names[$position]));
        }
    }

    /**
     * @dataProvider \Robier\Enum\Test\Unit\ValidDataProvider::allEnumerationsWithAllNames()
     */
    public function testItemCanBeUnset(string $enum): void
    {
        $all = call_user_func([$enum, 'all']);

        $collection = new Collection(...$all);

        $this->assertTrue(isset($collection[0]));
        unset($collection[0]);
        $this->assertFalse(isset($collection[0]));
    }
}
