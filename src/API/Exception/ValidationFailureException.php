<?php

namespace AmaTeam\EventSourcing\API\Exception;

use AmaTeam\EventSourcing\API\Event\EventContainer;
use AmaTeam\EventSourcing\API\Event\EventContainerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationFailureException extends RuntimeException
{
    const EVENT_TEMPLATE = 'Event %s has failed validation:';
    /**
     * @var ConstraintViolationListInterface
     */
    private $violations;

    public function __construct(
        ConstraintViolationListInterface $violations,
        $message = null,
        Throwable $previous = null
    ) {
        $this->violations = $violations;
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }

    public static function fromEventViolations(
        EventContainerInterface $event,
        ConstraintViolationListInterface $violations
    ): ValidationFailureException {
        $lines = [sprintf(static::EVENT_TEMPLATE, EventContainer::asString($event))];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $lines[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
        }
        $message = implode(PHP_EOL, $lines);
        return new ValidationFailureException($violations, $message);
    }
}
