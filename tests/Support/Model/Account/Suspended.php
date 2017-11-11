<?php

namespace AmaTeam\EventSourcing\Test\Support\Model\Account;

use AmaTeam\EventSourcing\API\Entity\EntityInterface;
use AmaTeam\EventSourcing\API\Event\EventMetadataInterface;
use AmaTeam\EventSourcing\API\Event\ValidatingEventInterface;
use AmaTeam\EventSourcing\Test\Support\Model\Account;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Suspended implements ValidatingEventInterface
{
    /**
     * @param EntityInterface|Account|null $entity
     * @param EventMetadataInterface $metadata
     * @return EntityInterface
     */
    public function apply(?EntityInterface $entity, EventMetadataInterface $metadata): EntityInterface
    {
        return $entity->setStatus(Account::STATUS_SUSPENDED);
    }

    /**
     * @param EntityInterface|Account $entity
     * @param EventMetadataInterface $metadata
     * @return null|ConstraintViolationListInterface
     */
    public function validate(
        ?EntityInterface $entity,
        EventMetadataInterface $metadata
    ): ?ConstraintViolationListInterface {
        if ($entity && $entity->getStatus() === Account::STATUS_ACTIVE) {
            return null;
        }
        if (!$entity) {
            $message = 'Can\'t suspend inexisting account';
            $violation = new ConstraintViolation($message, $message, [], null, '', null);
            return new ConstraintViolationList([$violation]);
        }
        $status = $entity->getStatus();
        $template = "Can't suspend account with status {0}";
        $message = str_replace($template, '{0}', $status);
        $violation = new ConstraintViolation($message, $template, [$status], $entity, 'status', $status);
        return new ConstraintViolationList([$violation]);
    }
}
