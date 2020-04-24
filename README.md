Enums
-----

This is yet another enum implementation in PHP.

This project aims to provide missing enums support with pure PHP without vertical inheritance.

**Note:** This enums can not be serialized.

I created this library as I got tired of creating enum values as class constants. I think
it's a bad way. Developer can by mistake compare 2 different enums or compare constants with
magic number all over the code.

Let's see the problem in actual code:
```php
<?php
class UserType {
    public const ADMIN = 1;
    public const REGULAR = 2;
}

class ReviewStatus {
    public const DRAFT = 1;
    public const PUBLISHED = 2;
    public const DELETED = 3;
}

$result1 = UserType::ADMIN == ReviewStatus::DRAFT; // evaluates to true
$result2 = UserType::REGULAR === ReviewStatus::PUBLISHED; // evaluates to true
```
This two classes holds same values in some constants (ADMIN & DRAFT and REGULAR & PUBLISHED). We can
make comparison as shown in snippet above, but we should not ever do that as conceptually that is wrong.
UserType does not have any connection to ReviewStatus and vice versa.

Objects can solve this problem really easy that's why this library is created.

Types of enum:
- **StringEnum** - constant values must be strings
- **CharEnum** - constant values must be strings containing only one character
- **IntegerEnum** - constant values must be integers
- **UnsignedIntegerEnum** - constant values must be positive integers (zero is also a "positive" integer)
- **MaskEnum** - enum holding multiple values (bitmask)

**Note:** Values of enum constants must not repeat as this miss the whole point of enums.

**Note:** In all enum types all values needs to be unique, if they are not, developer will get validation exception.

**Note:** Every enum type is validated only once and cached for further use.

Let's look at one integer enum.
```php
<?php

/**
 * @method static self admin()
 * @method static self regular()
 * @method bool isAdmin()
 * @method bool isRegular()
 */
final class UserType
{
    use \Robier\Enum\IntegerEnum;
    
    private const ADMIN = 1;
    private const REGULAR = 2;
}
```

As you can see there is 2 types of magic methods in enum:
- factory (`admin()` and `regular()` methods)
- checkers (`isAdmin()` and `isRegular()` methods)

Any magic method can be easily overwritten by concrete method.

If you use names for constants that have multiple words like `FOO_BAR`, magic methods would be cammelCased
ie. `fooBar` for factory and `isFooBar()` for checker.

**Note**: Constants names in enums **MUST** be defined like UPPER_SNAKE_CASE (it's also a standard in PHP).

List of handy methods:
- `static byName(string $name): self`
- `static byValue(mixed $value): self`
- `static byIndex(int $index): self`
- `static all(self ...$except): array`
- `static getNames(): array`
- `static getValues(): array`
- `static getRandom(): self`
- `name(): string`
- `value(): mixed`
- `equal(self $enum): bool`
- `any(self ...$enums): bool`

Mask enum is special one so there is few more methods in that enum:
- `allInOne(self ...$except): self`
- `contains(self $enum): bool`
- `containsAll(self ...$enums): bool`

Check methods description for more info.

### Examples of usage

```php
$userType = UserType::admin();

// check user type
$userType->isAdmin();
$userType->isRegular();

// check user type with another instance of UserType enum
$userType->equal(UserType::regular());

// check equality old fashioned way
$userType === UserType::regular();

// check user type if matching any of multiple instances of UsertType
$userType->anyOf(UserType::regular(), UserType::admin());

// create enum by providing name of constant
UserType::byName('admin');
UserType::byName('regular');

// creating enum by providing value of constant
UserType::byValue(1); // admin type
UserType::byValue(2); // regular type
```

### Guidelines

- Defined constants should not be public but private or protected. So they can not be used
by developer. Developer should only use methods defined on enum class.
- Keep enum definitions small and specific (do not put multiple concerns to one enum)
- Do not extend your enumerations, ie. make your enums final.

### Helper functions

There is also few helper functions in `Robier\Enum` namespace:
```
isEnum(string|object $enum): bool
getEnumType(string|object $enum): string // can throw Robier\Enum\Exception\NotEnumClass
isStringEnum(string|object $enum): bool
isCharEnum(string|object $enum): bool
isIntegerEnum(string|object $enum): bool
isUnsignedIntegerEnum(string|object $enum): bool
isMaskEnum(string|object $enum): bool
```

For more details check comments in file where functions are.

### Development

This project is dokerized. 
Before running tests you need to build docker container by using provided script form docker folder.

```bash
 $ docker/build
```

After build is finished you can run any command by using:

```bash
 $ docker/run %command%
```

for example:

```bash
 $ docker/run composer install
```

Additional tools for developing:
- `composer test` - runs all PHPUnit tests
- `composer test:coverage:html` - runs all PHPUnit tests and generates HTML report
- `composer test:infestation` - runs mutation tests and generates mutation score
- `composer phpstan` - runs phpstan on level 8 for src folder

Fill free to contribute.
