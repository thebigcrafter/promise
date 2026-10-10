<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use React\Promise\Deferred;
use function React\Promise\all;
use function React\Promise\any;
use function React\Promise\map;
use function React\Promise\reduce;
use function React\Promise\reject;
use function React\Promise\resolve;

function expectSame($expected, $actual, string $label): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($label . ' failed');
    }
}

$deferred = new Deferred();
$deferred->promise()->done(function ($value): void {
    expectSame('deferred', $value, 'Deferred resolution');
});
$deferred->resolve('deferred');

resolve(2)->then(function ($value) {
    return $value + 1;
})->done(function ($value): void {
    expectSame(3, $value, 'Promise chaining');
});

all([resolve(1), 2])->done(function ($values): void {
    expectSame([1, 2], $values, 'Promise all');
});

any([reject('first rejection'), resolve('winner')])->done(function ($value): void {
    expectSame('winner', $value, 'Promise any');
});

map([1, 2, 3], function ($value) {
    return $value * 2;
})->done(function ($values): void {
    expectSame([2, 4, 6], $values, 'Promise map');
});

reduce([1, 2, 3], function ($carry, $value) {
    return $carry + $value;
}, 0)->done(function ($value): void {
    expectSame(6, $value, 'Promise reduce');
});

$handled = false;
reject(new RuntimeException('expected'))->otherwise(function (RuntimeException $reason) use (&$handled) {
    $handled = $reason->getMessage() === 'expected';
    return 'recovered';
})->done(function ($value) use (&$handled): void {
    expectSame('recovered', $value, 'Rejection recovery');
    expectSame(true, $handled, 'Rejection handler');
});

echo "PASS: promise smoke checks\n";
