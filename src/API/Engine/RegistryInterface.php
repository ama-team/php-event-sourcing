<?php

namespace AmaTeam\EventSourcing\API\Engine;

use AmaTeam\EventSourcing\API\Entity\EntityDescriptorInterface;
use AmaTeam\EventSourcing\API\Event\EventVersionInterface;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;

/**
 * This interface defines mapping component that orchestrates runtime
 * class name to string type and vice versa conversions, so engine
 * would save event and entity names rather than their exact classes.
 */
interface RegistryInterface
{
    /**
     * @param EntityDescriptorInterface $descriptor
     */
    public function register(EntityDescriptorInterface $descriptor): void;

    /**
     * @param string $entityType
     * @param string $className
     * @return EventVersionInterface[]
     */
    public function getEventVersions(string $entityType, string $className): array;

    /**
     * @param string $entityType
     * @param string $eventType
     * @param int $version
     * @return string
     */
    public function getEventClassName(string $entityType, string $eventType, int $version): string;

    /**
     * @param string $className
     * @return string[]
     */
    public function getEntityTypes(string $className): array;

    /**
     * @param string $type
     * @return string
     */
    public function getEntityClass(string $type): string;
    public function normalizeId(IdentifierInterface $id): IdentifierInterface;
    public function denormalizeId(IdentifierInterface $id): IdentifierInterface;
}
