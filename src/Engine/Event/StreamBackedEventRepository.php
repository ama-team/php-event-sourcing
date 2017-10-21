<?php

namespace AmaTeam\EventSourcing\Engine\Event;

use AmaTeam\EventSourcing\API\Event\EventContainer;
use AmaTeam\EventSourcing\API\Event\EventContainerInterface;
use AmaTeam\EventSourcing\API\Event\EventMetadata;
use AmaTeam\EventSourcing\API\Event\EventRepositoryInterface;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Normalization\NormalizerInterface;
use AmaTeam\EventSourcing\API\Storage\Entry;
use AmaTeam\EventSourcing\API\Storage\EntryInterface;
use AmaTeam\EventSourcing\API\Storage\StreamStorageInterface;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class StreamBackedEventRepository implements EventRepositoryInterface, LoggerAwareInterface
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
    public function commit(EventContainerInterface $event): bool
    {
        return $this->storage->commit($this->normalize($event));
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

    private function normalize(EventContainerInterface $event): EntryInterface
    {
        $metadata = $event->getMetadata();
        return new Entry(
            $metadata->getEntityId(),
            $metadata->getIndex(),
            1,
            $this->normalizer->normalize($event->getEvent()),
            $metadata->getAcknowledgedAt(),
            $metadata->getOccurredAt(),
            // TODO: use registry for type mapping
            $metadata->getType()
        );
    }

    private function denormalize(EntryInterface $item): EventContainerInterface
    {
        $metadata = new EventMetadata(
            $item->getStream(),
            $item->getIndex(),
            $item->getType(),
            $item->getRecordedAt(),
            $item->getChangedAt()
        );
        $event = $this->normalizer->denormalize($item->getData(), $item->getType());
        return new EventContainer($event, $metadata);
    }
}
