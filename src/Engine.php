<?php

namespace AmaTeam\Bundle\EventSourcingBundle;

use AmaTeam\Bundle\EventSourcingBundle\API\EngineInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Entity\EntityContainerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\EventRepositoryInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Event\ListenerInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;
use AmaTeam\Bundle\EventSourcingBundle\API\Snapshot\SnapshotRepositoryInterface;
use AmaTeam\Bundle\EventSourcingBundle\Engine\Event\EventContainer;
use AmaTeam\Bundle\EventSourcingBundle\Engine\Event\EventMetadata;
use AmaTeam\Bundle\EventSourcingBundle\Engine\Action\MergeAction;
use AmaTeam\Bundle\EventSourcingBundle\Engine\Snapshot\NullSnapshotRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Engine implements EngineInterface
{
    use LoggerAwareTrait;
    /**
     * @var Options
     */
    private $options;
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
    private $listeners = [];

    /**
     * @param Options $options
     * @param EventRepositoryInterface $eventRepository
     * @param SnapshotRepositoryInterface $snapshotRepository
     */
    public function __construct(
        EventRepositoryInterface $eventRepository,
        SnapshotRepositoryInterface $snapshotRepository = null,
        Options $options = null
    ) {
        $this->events = $eventRepository;
        $this->snapshots = $snapshotRepository ?: new NullSnapshotRepository();
        $this->options = $options ?: new Options();
        $this->logger = new NullLogger();
    }

    public function get(IdentifierInterface $id): EntityContainerInterface
    {
        $snapshot = $this->snapshots->get($id);
        $version = $snapshot ? $snapshot->getMetadata()->getVersion() : 0;
        $remaining = $this->events->getEvents($id, $version);
        $action = new MergeAction($this->logger);
        $container = $action->execute($id, $snapshot, $remaining);
        if (sizeof($remaining) >= $this->options->getSnapshotInterval()) {
            $this->snapshots->save($container);
        }
        return $container;
    }

    public function save(
        IdentifierInterface $id,
        EventInterface $event,
        DateTimeInterface $occurredAt = null,
        int $attempts = 1
    ): bool {
        while ($attempts !== 0) {
            $index = $this->events->count($id) + 1;
            $metadata = new EventMetadata(
                $index,
                $id,
                new DateTimeImmutable(),
                $occurredAt
            );
            $container = new EventContainer($event, $metadata);
            if (!$this->events->save($container)) {
                $attempts--;
                continue;
            }
            // TODO: race condition, fetch entity at specific version
            $entity = $this->get($id);
            /** @var ListenerInterface $listener */
            foreach ($this->listeners as $listener) {
                $listener->accept($container, $entity);
            }
            return true;
        }
        return false;
    }

    public function getEvents(
        IdentifierInterface $id,
        $start = 0,
        $limit = -1
    ): array {
        return $this->events->getEvents($id, $start, $limit);
    }

    public function addListener(ListenerInterface $listener): EngineInterface
    {
        $this->listeners[] = $listener;
        return $this;
    }
}
