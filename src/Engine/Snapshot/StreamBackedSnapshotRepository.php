<?php

namespace AmaTeam\EventSourcing\Engine\Snapshot;

use AmaTeam\EventSourcing\API\Engine\RegistryInterface;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Normalization\NormalizerInterface;
use AmaTeam\EventSourcing\API\Snapshot\BasicSnapshotRepositoryInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotContainer;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotContainerInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotMetadata;
use AmaTeam\EventSourcing\API\Storage\Entry;
use AmaTeam\EventSourcing\API\Storage\EntryInterface;
use AmaTeam\EventSourcing\API\Storage\StreamStorageInterface;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class StreamBackedSnapshotRepository implements BasicSnapshotRepositoryInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var StreamStorageInterface
     */
    private $storage;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @param StreamStorageInterface $storage
     * @param NormalizerInterface $normalizer
     * @param RegistryInterface $registry
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        StreamStorageInterface $storage,
        NormalizerInterface $normalizer,
        RegistryInterface $registry,
        LoggerInterface $logger = null
    ) {
        $this->storage = $storage;
        $this->normalizer = $normalizer;
        $this->registry = $registry;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function fetch(QueryInterface $query): array
    {
        return array_map([$this, 'denormalize'], $this->storage->fetch($query));
    }

    /**
     * @inheritDoc
     */
    public function commit(SnapshotContainerInterface $snapshot): bool
    {
        return $this->storage->commit($this->normalize($snapshot));
    }

    /**
     * @inheritDoc
     */
    public function count(IdentifierInterface $id): int
    {
        return $this->storage->count($id);
    }

    /**
     * @inheritDoc
     */
    public function purge(IdentifierInterface $id): ?int
    {
        return $this->storage->purge($id);
    }

    private function normalize(SnapshotContainerInterface $snapshot): EntryInterface
    {
        $metadata = $snapshot->getMetadata();
        return new Entry(
            $metadata->getNormalizedEntityId(),
            $metadata->getIndex(),
            $metadata->getVersion(),
            $this->normalizer->normalize($snapshot->getEntity()),
            $metadata->getAcknowledgedAt(),
            $metadata->getOccurredAt(),
            $metadata->getCreatedAt()
        );
    }

    /**
     * @param EntryInterface $item
     * @return SnapshotContainerInterface
     *
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function denormalize(EntryInterface $item): SnapshotContainerInterface
    {
        $id = $this->registry->denormalizeId($item->getStream());
        $metadata = new SnapshotMetadata(
            $id,
            $item->getStream(),
            $item->getIndex(),
            $item->getVersion(),
            $item->getCreatedAt(),
            $item->getAcknowledgedAt(),
            $item->getOccurredAt()
        );
        $entity = $this->normalizer->denormalize($item->getData(), $id->getType());
        return new SnapshotContainer($entity, $metadata);
    }
}
