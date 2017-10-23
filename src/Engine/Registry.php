<?php

namespace AmaTeam\EventSourcing\Engine;

use AmaTeam\EventSourcing\API\Engine\RegistryInterface;
use AmaTeam\EventSourcing\API\Entity\EntityDescriptor;
use AmaTeam\EventSourcing\API\Entity\EntityDescriptorInterface;
use AmaTeam\EventSourcing\API\Event\EventDescriptor;
use AmaTeam\EventSourcing\API\Event\EventDescriptorInterface;
use AmaTeam\EventSourcing\API\Event\EventVersion;
use AmaTeam\EventSourcing\API\Exception\UnregisteredEntityException;
use AmaTeam\EventSourcing\API\Exception\UnregisteredEventException;
use AmaTeam\EventSourcing\API\Misc\Identifier;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;

class Registry implements RegistryInterface
{
    /**
     * @var EntityDescriptorInterface[]
     */
    private $typeIndex = [];
    /**
     * @var EntityDescriptorInterface[]
     */
    private $classIndex = [];
    /**
     * @var bool
     */
    private $directMappingAllowed = false;

    public function __construct(bool $directMappingAllowed = false)
    {
        $this->directMappingAllowed = $directMappingAllowed;
    }

    public function register(EntityDescriptorInterface $descriptor): void
    {
        $this->classIndex[$descriptor->getClassName()] = $descriptor;
        foreach ($descriptor->getTypes() as $type) {
            $this->typeIndex[$type] = $descriptor;
        }
    }

    public function getEntityTypes(string $className): array
    {
        return $this->findEntityByClass($className)->getTypes();
    }

    public function getEntityClass(string $type): string
    {
        return $this->findEntityByType($type)->getClassName();
    }

    public function getEventVersions(string $entityType, string $className): array
    {
        return $this->findEventByClass($entityType, $className)->getVersions();
    }

    public function getEventClassName(string $entityType, string $eventType, int $version): string
    {
        return $this->findEventByVersion($entityType, $eventType, $version)->getClassName();
    }

    public function findEntityByType(string $type): EntityDescriptorInterface
    {
        if (isset($this->typeIndex[$type])) {
            return $this->typeIndex[$type];
        }
        if ($this->directMappingAllowed && class_exists($type)) {
            return new EntityDescriptor($type, [$type], []);
        }
        $message = 'Could not find registered entity for type ' . $type;
        throw new UnregisteredEntityException($message);
    }

    public function findEntityByClass(string $className): EntityDescriptorInterface
    {
        if (isset($this->classIndex[$className])) {
            return $this->classIndex[$className];
        }
        if ($this->directMappingAllowed) {
            return new EntityDescriptor($className, [$className], []);
        }
        $message = 'Could not compute type for entity ' . $className;
        throw new UnregisteredEntityException($message);
    }

    public function findEventByClass(string $entityType, string $className): EventDescriptorInterface
    {
        $descriptor = $this->findEntityByType($entityType);
        foreach ($descriptor->getEvents() as $event) {
            if ($event->getClassName() === $className) {
                return $event;
            }
        }
        if ($this->directMappingAllowed) {
            return new EventDescriptor($className, new EventVersion($className, 1));
        }
        $message = 'Event of class %s is not registered for entity type %s';
        throw new UnregisteredEventException(sprintf($message, $className, $entityType));
    }

    public function findEventByVersion(string $entityType, string $eventType, int $version): EventDescriptorInterface
    {
        $descriptor = $this->findEntityByType($entityType);
        foreach ($descriptor->getEvents() as $event) {
            foreach ($event->getVersions() as $candidate) {
                if ($candidate->getType() === $eventType && $candidate->getVersion() === $version) {
                    return $event;
                }
            }
        }
        if ($this->directMappingAllowed && class_exists($eventType)) {
            return new EventDescriptor($eventType, new EventVersion($eventType, 1));
        }
        $template = 'Could not find event %s:%d for entity type %s';
        $message = sprintf($template, $eventType, $version, $entityType);
        throw new UnregisteredEventException($message);
    }

    public function normalizeId(IdentifierInterface $id): IdentifierInterface
    {
        $type = reset($this->getEntityTypes($id->getType()));
        return new Identifier($type, $id->getId());
    }

    public function denormalizeId(IdentifierInterface $id): IdentifierInterface
    {
        $className = $this->getEntityClass($id->getType());
        return new Identifier($className, $id->getId());
    }
}
