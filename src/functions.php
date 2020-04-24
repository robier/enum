<?php

declare(strict_types = 1);

namespace Robier\Enum;

use ReflectionClass;
use Robier\Enum\Exception\NotEnumClass;
use Throwable;

/**
 * Check if given class name or object is enum type by checking used traits.
 *
 * @var string|object $enum
 */
function isEnum($enum): bool
{
    try {
        getEnumType($enum);
        return true;
    } catch (NotEnumClass $e) {
        // noop
    }

    return false;
}

/**
 * Get enum type for given class name or object. If provided class/object is not enum, exception is thrown.
 *
 * @param string|object $enum
 * @throws NotEnumClass
 */
function getEnumType($enum): string
{
    $existingTraits = [
        StringEnum::class,
        CharEnum::class,
        IntegerEnum::class,
        UnsignedIntegerEnum::class,
        MaskEnum::class,
    ];

    try {
        $reflection = new ReflectionClass($enum);
    } catch (Throwable $e) {
        throw NotEnumClass::new($enum, $e);
    }

    $traits = $reflection->getTraitNames();

    foreach ($existingTraits as $trait) {
        if(in_array($trait, $traits, true)) {
            return $trait;
        }
    }

    foreach ($traits as $trait) {
        $enumType = getEnumType($trait);
        if(null !== $enumType) {
            return $enumType;
        }
    }

    throw NotEnumClass::new($enum);
}

/**
 * @param string|object $enum
 */
function isStringEnum($enum): bool
{
    try {
        return StringEnum::class === getEnumType($enum);
    } catch (NotEnumClass $e) {
        // noop
    }

    return false;
}

/**
 * @param string|object $enum
 */
function isCharEnum($enum): bool
{
    try {
        return CharEnum::class === getEnumType($enum);
    } catch (NotEnumClass $e) {
        // noop
    }

    return false;
}

/**
 * @param string|object $enum
 */
function isIntegerEnum($enum): bool
{
    try {
        return IntegerEnum::class === getEnumType($enum);
    } catch (NotEnumClass $e) {
        // noop
    }

    return false;
}

/**
 * @param string|object $enum
 */
function isUnsignedIntegerEnum($enum): bool
{
    try {
        return UnsignedIntegerEnum::class === getEnumType($enum);
    } catch (NotEnumClass $e) {
        // noop
    }

    return false;
}

/**
 * @param string|object $enum
 */
function isMaskEnum($enum): bool
{
    try {
        return MaskEnum::class === getEnumType($enum);
    } catch (NotEnumClass $e) {
        // noop
    }

    return false;
}
