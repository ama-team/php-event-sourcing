<?php

namespace AmaTeam\EventSourcing\Engine\Snapshot;

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
     * @param StreamStorageInterface $storage
     * @param NormalizerInterface $normalizer
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        StreamStorageInterface $storage,
        NormalizerInterface $normalizer,
        LoggerInterface $logger = null
    ) {
        $this->storage = $storage;
        $this->normalizer = $normalizer;
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
            $metadata->getEntityId(),
            $metadata->getIndex(),
            $metadata->getVersion(),
            $this->normalizer->normalize($snapshot->getEntity()),
            $metadata->getAcknowledgedAt(),
            $metadata->getOccurredAt(),
            // TODO: use registry for type mapping
            get_class($snapshot->getEntity())
        );
    }

    private function denormalize(EntryInterface $item): SnapshotContainerInterface
    {
        $metadata = new SnapshotMetadata(
            $item->getStream(),
            $item->getIndex(),
            $item->getVersion(),
            $item->getRecordedAt(),
            $item->getChangedAt()
        );
        $entity = $this->normalizer->denormalize($item->getData(), $item->getType());
        return new SnapshotContainer($entity, $metadata);
    }
}
