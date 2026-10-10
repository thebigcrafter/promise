<?php

namespace React\Promise;

/**
 * @deprecated 2.8.0 External usage of FulfilledPromise is deprecated, use `resolve()` instead.
 */
class FulfilledPromise implements ExtendedPromiseInterface, CancellablePromiseInterface
{
    /** @var mixed */
    private $value;

    /** @param mixed $value */
    public function __construct($value = null)
    {
        if ($value instanceof PromiseInterface) {
            throw new \InvalidArgumentException('You cannot create React\Promise\FulfilledPromise with a promise. Use React\Promise\resolve($promiseOrValue) instead.');
        }

        $this->value = $value;
    }

    public function then(?callable $onFulfilled = null, ?callable $onRejected = null, ?callable $onProgress = null): ExtendedPromiseInterface
    {
        if (null === $onFulfilled) {
            return $this;
        }

        try {
            return resolve($onFulfilled($this->value));
        } catch (\Throwable $exception) {
            return new RejectedPromise($exception);
        }
    }

    public function done(?callable $onFulfilled = null, ?callable $onRejected = null, ?callable $onProgress = null): void
    {
        if (null === $onFulfilled) {
            return;
        }

        $result = $onFulfilled($this->value);

        if ($result instanceof ExtendedPromiseInterface) {
            $result->done();
        }
    }

    public function otherwise(callable $onRejected): ExtendedPromiseInterface
    {
        return $this;
    }

    public function always(callable $onFulfilledOrRejected): ExtendedPromiseInterface
    {
        return $this->then(function ($value) use ($onFulfilledOrRejected) {
            return resolve($onFulfilledOrRejected())->then(function () use ($value) {
                return $value;
            });
        });
    }

    public function progress(callable $onProgress): ExtendedPromiseInterface
    {
        return $this;
    }

    public function cancel(): void
    {
    }
}
