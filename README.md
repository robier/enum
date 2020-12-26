Enums
-----

This is yet another enum implementation in PHP.

This project aims to provide missing enum support with pure PHP without vertical inheritance.

**Note:** These enums can not be serialized.

I created this library as I got tired of creating enum values as class constants. I think
it's a bad way. Developer can compare 2 different enums by mistake or compare constants with
magic numbers all over the code.

Let's see the problem in the actual code:
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
These two classes hold the same values in some constants (ADMIN & DRAFT and REGULAR & PUBLISHED). We can
make a comparison as shown in snippet above, but we should not ever do that as it's conceptually wrong.
UserType does not have any connection to ReviewStatus and vice versa.

Objects can solve this problem really easy, that's why this library was created.

Types of enums:
- **StringEnum** - constant values must be strings
- **CharEnum** - constant values must be strings containing only one character
- **IntegerEnum** - constant values must be integers
- **UnsignedIntegerEnum** - constant values must be positive integers (zero is also a "positive" integer)
- **MaskEnum** - enum holding multiple values (bitmask)

**Note:** Values of enum constants must not repeat.

**Note:** In all enum types, every value needs to be unique, if they are not, developer will get a validation exception.

**Note:** Every enum type is validated only once and cached for further use.

Let's look at one integer enum.
```php
<?php

/**
 * @method static self admin()
 * @method bool isAdmin()
 * @method bool notAdmin()
 * 
 * @method static self regular()
 * @method bool isRegular()
 * @method bool notRegular()
 */
final class UserType
{
    use \Robier\Enum\IntegerEnum;
    
    private const ADMIN = 1;
    private const REGULAR = 2;
}
```

As you can see there are 2 types of magic methods in an enum:
- factory (`admin()` and `regular()` methods)
- checkers (`isAdmin()`,`isRegular()`, `notAdmin()`, `notRegular()` methods)

Any magic method can be easily overwritten by a concrete method.

If you use names for constants that have multiple words like `FOO_BAR`, magic methods would be camelCased
ie. `fooBar` for factory and `isFooBar()`/`notFooBar()` for checker.

**Note**: Constant names in enums **MUST** be defined like UPPER_SNAKE_CASE (it's also a standard in PHP).

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

Mask enum is special, so there are a few more methods:
- `allInOne(self ...$except): self`
- `contains(self $enum): bool`
- `containsAll(self ...$enums): bool`
- `names(): array`
- does not have `name()` method

Check method descriptions for more info.

### Examples of usage

```php
<?php
$userType = UserType::admin();

// check user type
$userType->isAdmin();
$userType->isRegular();
$userType->notAdmin();
$userType->notRegular();

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

### Features
With features, you can change enum default behaviour.

#### Undefined

By default, enums will throw an exception if you try to create one by non-existing name/value/index.
When using **Undefined** gadget enum will allow not-existing name/value/index. Enum will be in undefined
state. Handy if you have some legacy system that can give enum values you do not care about. Or even if 
you are using getters, so every time you call a getter, you get a genuine object even you do not have
real value yet.

**Note**: Two undefined enums are never equal, as they have undetermined value!

**Note**: Name of undefined enum will be `UNDEFINED`.

**Note**: When using Undefined feature, you can not have constant with name `UNDEFINED`, it will throw an exception.

**Note**: Two undefined enums are same objects because of cache, but semantically they are not. When you are using equal() method it will return false if any or both enums are undefined. 

It brings few handy methods:
- `static undefined(): static` - creates undefined enum
- `isUndefined(): bool` - check if current instance **is** undefined one
- `notUndefined(): bool` - check if current instance **is not** undefined one

How to implement:

```php
<?php

/**
 * @method static self admin()
 * @method bool isAdmin()
 * @method bool notAdmin()
 * 
 * @method static self regular()
 * @method bool isRegular()
 * @method bool notRegular()
 */
final class UserType
{
    use \Robier\Enum\IntegerEnum;
    use \Robier\Enum\Feature\Undefined;
    
    private const ADMIN = 1;
    private const REGULAR = 2;
}
```

How to use:
```php
<?php
$userType = UserType::undefined(); // UserType instance
$userType->isUndefined(); // true
$userType->notUndefined(); // false
$userType->isAdmin(); // false
$userType->notAdmin(); // true
$userType->isRegular(); // false
$userType->notRegular(); // true
```

### Error handling

All exception thrown from this library has implemented interface `Robier\Enum\Exception` for easier error handling.

```php
<?php

try {
    UserType::byIndex(-5);
} catch (\Robier\Enum\Exception $exception) {
    // catch all exceptions thrown by enums
}
```

### Guidelines

- Defined constants should not be public but private or protected, so they can not be used
by developer. Developer should only use methods defined in the enum class.
- Keep enum definitions small and specific (do not put multiple concerns to one enum)
- Do not extend your enumerations, ie. make your enums final.
- Do not combine different data types of constant values in one enum.
- Group enum methods together by name, so you have all methods from one name in one spot (use bin/enum to generate DocBlock).

### Helper functions

There are also a few helper functions in `Robier\Enum` namespace:
```
isEnum(string|object $enum): bool
getEnumType(string|object $enum): string // can throw Robier\Enum\Exception\NotEnumClass
isStringEnum(string|object $enum): bool
isCharEnum(string|object $enum): bool
isIntegerEnum(string|object $enum): bool
isUnsignedIntegerEnum(string|object $enum): bool
isMaskEnum(string|object $enum): bool
```

For more details, check the comments in files where the functions are located.

### DockBlocks

Let's say you have enum with all countries inside Europe Union. There is ATM 27 countries, you want to have all enum
methods witten inside DocBlock of your enum, so you can leverage IDE autocompletion. As it's 3 methods per country we
get total of 81 method that we need to write inside our DocBlock. You can easily generate all of them just by providing
FQN of enum to enum CLI script. It will generate in standard output all possible methods.

```bash
./vendor/bin/enum "\Robier\Enum\Test\Data\Integers\ValidIntegerEnum"
```

Would generate: 
```text
/**
 * @method static self one()
 * @method bool isOne()
 * @method bool notOne()
 *
 * @method static self oneTwo()
 * @method bool isOneTwo()
 * @method bool notOneTwo()
 *
 * @method static self oneTwoThree()
 * @method bool isOneTwoThree()
 * @method bool notOneTwoThree()
 */
```

You need to copy/paste that DocBlock before enum class declaration inside file.

**Note:** It will not generate DocBlock if method is defined inside class.

### Development

This project is dockerized. 
Before running tests you need to build a docker container by using provided script from the docker folder.

```bash
 $ docker/build
```

After the build is finished, you can run any command by using:

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

Feel free to contribute.
