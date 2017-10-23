<?php

namespace AmaTeam\EventSourcing\Engine\Operation;

use AmaTeam\EventSourcing\API\Engine\RegistryInterface;
use AmaTeam\EventSourcing\API\Entity\EntityContainer;
use AmaTeam\EventSourcing\API\Entity\EntityContainerInterface;
use AmaTeam\EventSourcing\API\Entity\EntityMetadata;
use AmaTeam\EventSourcing\API\Event\EventContainer;
use AmaTeam\EventSourcing\API\Event\EventContainerInterface;
use AmaTeam\EventSourcing\API\Event\EventInterface;
use AmaTeam\EventSourcing\API\Event\EventMetadata;
use AmaTeam\EventSourcing\API\Event\EventRepositoryInterface;
use AmaTeam\EventSourcing\API\Event\ListenerInterface;
use AmaTeam\EventSourcing\API\Exception\MultiException;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotContainer;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotMetadata;
use AmaTeam\EventSourcing\API\Snapshot\SnapshotRepositoryInterface;
use AmaTeam\EventSourcing\Engine\Helper\Stringifier;
use AmaTeam\EventSourcing\Options;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

class Commit implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var EventRepositoryInterface
     */
    private $events;
    /**
     * @var SnapshotRepositoryInterface
     */
    private $snapshots;
    /**
     * @var ListenerInterface[]
     */
    private $listeners;
    /**
     * @var Options
     */
    private $options;
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * @param EventRepositoryInterface $events
     * @param SnapshotRepositoryInterface $snapshots
     * @param RegistryInterface $registry
     * @param ListenerInterface[] $listeners
     * @param Options $options
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        EventRepositoryInterface $events,
        SnapshotRepositoryInterface $snapshots,
        RegistryInterface $registry,
        array $listeners,
        Options $options,
        LoggerInterface $logger = null
    ) {
        $this->events = $events;
        $this->snapshots = $snapshots;
        $this->registry = $registry;
        $this->listeners = $listeners;
        $this->options = $options;
        $this->logger = $logger ?: new NullLogger();
    }

    public function execute(
        IdentifierInterface $id,
        EventInterface $event,
        DateTimeInterface $occurredAt = null
    ): ?EntityContainerInterface {
        $retrieve = new Retrieval($this->events, $this->snapshots, $this->registry, $this->logger);
        $application = new EventApplication();
        $entity = $retrieve->execute($id);
        $container = $this->containerize($entity, $event, $occurredAt);
        $entity = $application->execute($entity, $container);
        if (!$this->events->commit($container)) {
            return null;
        }
        $suppressed = $this->applyListeners($container, $entity);
        try {
            $entity = $this->snapshot($entity);
        } catch (Throwable $e) {
            $suppressed[] = $e;
        }
        if (empty($suppressed)) {
            return $entity;
        }
        if (sizeof($suppressed) === 1) {
            throw reset($suppressed);
        }
        $message = 'Exceptions were caught while post-processing event ' . EventContainer::asString($container);
        throw new MultiException($suppressed, $message);
    }

    private function containerize(
        EntityContainerInterface $entity,
        EventInterface $event,
        DateTimeInterface $occurredAt = null
    ): EventContainerInterface {
        $source = $entity->getMetadata();
        $versions = $this->registry->getEventVersions($source->getNormalizedId()->getType(), get_class($event));
        $version = reset($versions);
        $metadata = new EventMetadata(
            $source->getId(),
            $source->getNormalizedId(),
            ($source->getVersion() ?: 0) + 1,
            $version->getType(),
            $version->getVersion(),
            get_class($event),
            new DateTimeImmutable(),
            $occurredAt
        );
        return new EventContainer($event, $metadata);
    }

    private function applyListeners(EventContainerInterface $event, EntityContainerInterface $entity): array
    {
        $suppressed = [];
        foreach ($this->listeners as $listener) {
            $context = [
                'listener' => Stringifier::stringify($listener),
                'event' => EventContainer::asString($event),
                'entity' => EntityContainer::asString($entity)
            ];
            $this->logger->debug('Applying listener {listener} to event {event}', $context);
            try {
                $listener->accept($event, $entity);
            } catch (Throwable $e) {
                $suppressed[] = $e;
                $context['exception'] = $e;
                $message = 'Exception caught while applying listener {listener} to event {event}';
                $this->logger->error($message, $context);
            }
        }
        return $suppressed;
    }

    private function snapshot(EntityContainerInterface $entity): EntityContainerInterface
    {
        // TODO: delete extra snapshots if possible
        $source = $entity->getMetadata();
        $difference = ($source->getVersion() ?: 0) - ($source->getSnapshotVersion() ?: 0);
        if ($difference < $this->options->getSnapshotInterval()) {
            return $entity;
        }
        $metadata = SnapshotMetadata::fromEntityMetadata($source);
        $snapshot = new SnapshotContainer($entity->getEntity(), $metadata);
        if (!$this->snapshots->commit($snapshot)) {
            return $entity;
        }
        $target = EntityMetadata::from($source)
            ->withSnapshotVersion($metadata->getVersion())
            ->withSnapshotIndex($metadata->getIndex());
        return new EntityContainer($entity->getEntity(), $target);
    }
}
