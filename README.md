# promise

`promise` is a lightweight implementation of CommonJS Promises/A for PHP.
It provides the `React\Promise` namespace and functional helpers for composing
fulfilled, rejected and asynchronous values.

## Installation

Install the Composer package:

```sh
composer require thebigcrafterx/promise
```

The package requires PHP 7.1 or newer. Its public classes and functions remain
under the `React\Promise` namespace.

## Usage

### Resolve and chain values

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use function React\Promise\resolve;

resolve(2)
    ->then(function (int $value): int {
        return $value + 1;
    })
    ->done(function (int $value): void {
        echo $value; // 3
    });
```

### Create a deferred promise

```php
use React\Promise\Deferred;

$deferred = new Deferred();

$deferred->promise()->done(function ($value): void {
    echo $value;
});

$deferred->resolve('ready');
```

### Combine promises and values

The helper functions accept promises, values or a mixture of both:

```php
use function React\Promise\all;
use function React\Promise\resolve;

all([resolve('first'), 'second'])->done(function (array $values): void {
    // ['first', 'second']
});
```

Available helpers include `resolve`, `reject`, `all`, `race`, `any`, `some`,
`map` and `reduce`. Promise instances support `then()`, `done()`,
`otherwise()` and `always()`; cancellable promises also support `cancel()`.

## Development

Install the development dependencies and run the repository checks:

```sh
composer install
composer test
composer analyse
```

`composer test` runs the smoke contract in `tests/smoke.php`, while
`composer analyse` runs PHPStan using `phpstan.neon.dist`.

## License

This project is released under the [MIT License](LICENSE).
