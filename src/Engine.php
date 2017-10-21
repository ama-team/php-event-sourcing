<?php

namespace AmaTeam\EventSourcing;

use AmaTeam\EventSourcing\API\EngineInterface;
use AmaTeam\EventSourcing\API\Entity\EntityContainerInterface;
use AmaTeam\EventSourcing\API\Event\EventInterface;
use AmaTeam\EventSourcing\API\Event\EventRepositoryInterface;
use AmaTeam\EventSourcing\API\Event\ListenerInterface;
use AmaTeam\EventSourcing\API\Misc\Identifier;
use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use AmaTeam\EventSourcing\API\Misc\PurgeReport;
use AmaTeam\EventSourcing\API\Misc\PurgeReportInterface;
use AmaTeam\EventSourcing\API\Snapshot\BasicSnapshotRepositoryInterface;
use AmaTeam\EventSourcing\API\Storage\QueryInterface;
use AmaTeam\EventSourcing\Engine\Helper\Stringifier;
use AmaTeam\EventSourcing\Engine\Helper\SnapshotRepositoryNormalizer;
use AmaTeam\EventSourcing\Engine\Operation\Commit;
use AmaTeam\EventSourcing\Engine\Operation\Retrieval;
use DateTimeInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Engine implements EngineInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var EventRepositoryInterface
     */
    private $events;
    /**
     * @var BasicSnapshotRepositoryInterface
     */
    private $snapshots;
    /**
     * @var ListenerInterface[]
     */
    private $listeners = [];
    /**
     * @var Options
     */
    private $options;

    /**
     * @param EventRepositoryInterface $eventRepository
     * @param BasicSnapshotRepositoryInterface $snapshotRepository
     * @param Options $options
     * @param LoggerInterface|null $logger
     */
    public function __construct(
        EventRepositoryInterface $eventRepository,
        BasicSnapshotRepositoryInterface $snapshotRepository = null,
        Options $options = null,
        LoggerInterface $logger = null
    ) {
        $this->options = $options ?: new Options();
        $snapshotRepository = SnapshotRepositoryNormalizer::normalize($snapshotRepository);
        $this->events = $eventRepository;
        $this->snapshots = $snapshotRepository;
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @inheritDoc
     */
    public function get(IdentifierInterface $id, int $version = null): EntityContainerInterface
    {
        $operation = new Retrieval($this->events, $this->snapshots, $this->logger);
        return $operation->execute($id, $version);
    }

    /**
     * @inheritDoc
     */
    public function commit(
        IdentifierInterface $id,
        EventInterface $event,
        DateTimeInterface $occurredAt = null,
        int $attempts = 1
    ): ?EntityContainerInterface {
        $operation = new Commit($this->events, $this->snapshots, $this->listeners, $this->options, $this->logger);
        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            $context = [
                'attempt' => $attempt,
                'attempts' => $attempts,
                'id' => Identifier::asString($id),
                'event' => Stringifier::stringify($event)
            ];
            $message = 'Trying to commit event {event} against entity {id} (attempt #{attempt}/{attempts})';
            $this->logger->debug($message, $context);
            $result = $operation->execute($id, $event, $occurredAt);
            if ($result) {
                return $result;
            }
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function setLogger(LoggerInterface $logger)
    {
        $targets = [$this->events, $this->snapshots];
        foreach ($targets as $target) {
            if ($target instanceof LoggerAwareInterface) {
                $target->setLogger($logger);
            }
        }
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function purge(IdentifierInterface $id): PurgeReportInterface
    {
        return new PurgeReport($this->events->purge($id), $this->snapshots->purge($id));
    }

    /**
     * @inheritDoc
     */
    public function addListener(ListenerInterface $listener): EngineInterface
    {
        $this->listeners[] = $listener;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getEvents(QueryInterface $query): array
    {
        return $this->events->fetch($query);
    }

    /**
     * @inheritDoc
     */
    public function getSnapshots(QueryInterface $query): array
    {
        return $this->snapshots->fetch($query);
    }
}
