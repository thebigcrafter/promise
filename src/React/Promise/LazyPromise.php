<?php

namespace React\Promise;

/**
 * @deprecated 2.8.0 LazyPromise is deprecated and should not be used anymore.
 */
class LazyPromise implements ExtendedPromiseInterface, CancellablePromiseInterface
{
    /** @var callable */
    private $factory;
    /** @var ExtendedPromiseInterface|null */
    private $promise;

    public function __construct(callable $factory)
    {
        $this->factory = $factory;
    }

    public function then(?callable $onFulfilled = null, ?callable $onRejected = null, ?callable $onProgress = null): ExtendedPromiseInterface
    {
        return resolve($this->promise()->then($onFulfilled, $onRejected, $onProgress));
    }

    public function done(?callable $onFulfilled = null, ?callable $onRejected = null, ?callable $onProgress = null): void
    {
        $this->promise()->done($onFulfilled, $onRejected, $onProgress);
    }

    public function otherwise(callable $onRejected): ExtendedPromiseInterface
    {
        return $this->promise()->otherwise($onRejected);
    }

    public function always(callable $onFulfilledOrRejected): ExtendedPromiseInterface
    {
        return $this->promise()->always($onFulfilledOrRejected);
    }

    public function progress(callable $onProgress): ExtendedPromiseInterface
    {
        return $this->promise()->progress($onProgress);
    }

    public function cancel(): void
    {
        $promise = $this->promise();
        if ($promise instanceof CancellablePromiseInterface) {
            $promise->cancel();
        }
    }

    /**
     * @internal
     * @see Promise::settle()
     */
    public function promise(): ExtendedPromiseInterface
    {
        if (null === $this->promise) {
            try {
                $this->promise = resolve(\call_user_func($this->factory));
            } catch (\Throwable $exception) {
                $this->promise = new RejectedPromise($exception);
            }
        }

        return $this->promise;
    }
}
