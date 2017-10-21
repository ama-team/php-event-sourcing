<?php

namespace AmaTeam\EventSourcing\API\Exception;

use RuntimeException;
use Throwable;

class MultiException extends RuntimeException
{
    /**
     * @var Throwable[]
     */
    private $exceptions;

    /**
     * @param Throwable[] $exceptions
     * @param string|null $message
     */
    public function __construct(array $exceptions, $message = null)
    {
        $message = $message ?: 'Several exceptions caught at once';
        $previous = end($exceptions) ?: null;
        parent::__construct($message, 0, $previous);
        $this->exceptions = $exceptions;
    }

    /**
     * @return Throwable[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }
}
