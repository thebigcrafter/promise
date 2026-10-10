<?php

namespace React\Promise;

class Deferred implements PromisorInterface
{
    /** @var Promise|null */
    private $promise;
    /** @var callable|null */
    private $resolveCallback;
    /** @var callable|null */
    private $rejectCallback;
    /** @var callable|null */
    private $notifyCallback;
    /** @var callable|null */
    private $canceller;

    public function __construct(?callable $canceller = null)
    {
        $this->canceller = $canceller;
    }

    public function promise(): PromiseInterface
    {
        if (null === $this->promise) {
            $this->promise = new Promise(function (callable $resolve, callable $reject, callable $notify) {
                $this->resolveCallback = $resolve;
                $this->rejectCallback  = $reject;
                $this->notifyCallback  = $notify;
            }, $this->canceller);
            $this->canceller = null;
        }

        return $this->promise;
    }

    /** @param mixed $value */
    public function resolve($value = null): void
    {
        $this->promise();

        if ($this->resolveCallback !== null) {
            \call_user_func($this->resolveCallback, $value);
        }
    }

    /** @param mixed $reason */
    public function reject($reason = null): void
    {
        $this->promise();

        if ($this->rejectCallback !== null) {
            \call_user_func($this->rejectCallback, $reason);
        }
    }

    /**
     * @deprecated 2.6.0 Progress support is deprecated and should not be used anymore.
     * @param mixed $update
     */
    /** @param mixed $update */
    public function notify($update = null): void
    {
        $this->promise();

        if ($this->notifyCallback !== null) {
            \call_user_func($this->notifyCallback, $update);
        }
    }

    /**
     * @deprecated 2.2.0
     * @see Deferred::notify()
     */
    /** @param mixed $update */
    public function progress($update = null): void
    {
        $this->notify($update);
    }
}
