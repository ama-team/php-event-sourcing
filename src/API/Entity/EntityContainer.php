<?php

namespace AmaTeam\EventSourcing\API\Entity;

use AmaTeam\EventSourcing\API\Misc\Identifier;

class EntityContainer implements EntityContainerInterface
{
    /**
     * @var object|null
     */
    private $entity;
    /**
     * @var EntityMetadataInterface
     */
    private $metadata;

    /**
     * @param EntityInterface|null $entity
     * @param EntityMetadataInterface $metadata
     */
    public function __construct(?EntityInterface $entity, EntityMetadataInterface $metadata)
    {
        $this->entity = $entity;
        $this->metadata = $metadata;
    }

    /**
     * @return EntityInterface|null
     */
    public function getEntity(): ?EntityInterface
    {
        return $this->entity;
    }

    /**
     * @return EntityMetadataInterface
     */
    public function getMetadata(): EntityMetadataInterface
    {
        return $this->metadata;
    }

    public function __toString(): string
    {
        return static::asString($this);
    }

    public static function asString(EntityContainerInterface $container): string
    {
        $id = Identifier::asString($container->getMetadata()->getId());
        return 'Entity ' . $id . ', version ' . $container->getMetadata()->getVersion();
    }
}
