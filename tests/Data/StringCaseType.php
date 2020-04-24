<?php declare(strict_types = 1);

namespace Robier\Enum\Test\Data;

use Robier\Enum\StringEnum;

/**
 * @internal
 *
 * @method static self camel()
 * @method static self pascal()
 * @method static self upperSnake()
 * @method static self lowerSnake()
 * @method static self upperKebab()
 * @method static self lowerKebab()
 * @method static self upperSpace()
 * @method static self lowerSpace()
 * @method bool isCamel()
 * @method bool isPascal()
 * @method bool isUpperCase()
 * @method bool isLowerCase()
 * @method bool isUpperKebab()
 * @method bool isLowerKebab()
 * @method bool isUpperSpace()
 * @method bool isLowerSpace()
 */
final class StringCaseType
{
    use StringEnum;

    private const CAMEL = 'camelCase';
    private const PASCAL = 'PascalCase';
    private const UPPER_SNAKE = 'UPPER_SNAKE_CASE';
    private const LOWER_SNAKE = 'lower_snake_case';
    private const UPPER_KEBAB = 'UPPER-KEBAB-CASE';
    private const LOWER_KEBAB = 'lower-kebab-case';
    private const UPPER_SPACE = 'UPPER SPACE CASE';
    private const LOWER_SPACE = 'lower space case';
}
