<?php

namespace React\Promise;

/** @phpstan-consistent-constructor */
class UnhandledRejectionException extends \RuntimeException
{
    /** @var mixed */
    private $reason;

    /** @param mixed $reason */
    public static function resolve($reason): \Throwable
    {
        if ($reason instanceof \Exception || $reason instanceof \Throwable) {
            return $reason;
        }

        return new static($reason);
    }

    /** @param mixed $reason */
    public function __construct($reason)
    {
        $this->reason = $reason;

        $message = \sprintf('Unhandled Rejection: %s', \json_encode($reason));

        parent::__construct($message, 0);
    }

    /** @return mixed */
    public function getReason()
    {
        return $this->reason;
    }
}
