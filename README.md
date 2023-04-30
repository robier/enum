Enums
-----

~~This is yet another enum implementation in PHP.~~

When we did not have enums in PHP this library was trying to fix that. Now we have enums
with PHP version 8.1 so there is no need for "yet another enum implementation". This
library was changed to add "missing" features to already existing enums.

Supported features:

| **Name**       | **Trait**                     | **Description**                                                                                                                                                                                                                                                   |
|----------------|-------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Default        | \Robier\Enum\HasDefault       | Adds 2 new methods to the enum:<br>- `try(int\|string $value, self $default): self`<br>- `tryName(string $name, self $default): self`                                                                                                                             |
| Random         | \Robier\Enum\HasRandom        | Adds 1 new method:<br>- `random(self ...$exclude): self`                                                                                                                                                                                                          |
| Boolean checks | \Robier\Enum\HasBooleanChecks | Adds 1 new method and 2 methods for every case in enum.<br>New method is ´any(self ...$test):bool´ and other methods are magic. Let's say you have ADMIN_USER case in enum, then new magic methods would be:<br>- `isAdminUser():bool`<br>- `notAdminUser():bool` |
| All            | \Robier\Enum\HasAll           | Have all already mentioned enum enhancements.                                                                                                                                                                                                                     |

When using "Boolean checks", you can easily create a dock blocks for magic functions so your IDE can be
developer friendly.

```bash
$ vendor/bin/enum [FQN enum]
```
It will just generate all magic methods in stdout. It will not change actual files.

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
- `XDEBUG_MODE=coverage composer test:coverage:html` - runs all PHPUnit tests and generates HTML report
- `composer test:infestation` - runs mutation tests and generates mutation score

Feel free to contribute.
