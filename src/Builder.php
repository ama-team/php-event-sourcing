<?php

namespace AmaTeam\EventSourcing;

use AmaTeam\EventSourcing\API\EngineInterface;
use AmaTeam\EventSourcing\API\Event\EventRepositoryInterface;
use AmaTeam\EventSourcing\API\Exception\InvalidConfigurationException;
use AmaTeam\EventSourcing\API\Normalization\NormalizerInterface;
use AmaTeam\EventSourcing\API\Snapshot\BasicSnapshotRepositoryInterface;
use AmaTeam\EventSourcing\API\Storage\StreamStorageInterface;
use AmaTeam\EventSourcing\Engine\Event\StreamBackedEventRepository;
use AmaTeam\EventSourcing\Engine\Snapshot\StreamBackedSnapshotRepository;
use Psr\Log\LoggerInterface;

class Builder
{
    /**
     * @var StreamStorageInterface|null
     */
    private $eventStorage;
    /**
     * @var EventRepositoryInterface|null
     */
    private $eventRepository;
    /**
     * @var StreamStorageInterface|null
     */
    private $snapshotStorage;
    /**
     * @var BasicSnapshotRepositoryInterface|null
     */
    private $snapshotRepository;
    /**
     * @var NormalizerInterface|null
     */
    private $normalizer;
    /**
     * @var Options|null
     */
    private $options;
    /**
     * @var LoggerInterface|null
     */
    private $logger;

    /**
     * @param StreamStorageInterface $eventStorage
     * @return $this
     */
    public function withEventStorage(StreamStorageInterface $eventStorage): Builder
    {
        $this->eventStorage = $eventStorage;
        return $this;
    }

    /**
     * @param EventRepositoryInterface $eventRepository
     * @return $this
     */
    public function withEventRepository(EventRepositoryInterface $eventRepository): Builder
    {
        $this->eventRepository = $eventRepository;
        return $this;
    }

    /**
     * @param StreamStorageInterface $snapshotStorage
     * @return $this
     */
    public function withSnapshotStorage(StreamStorageInterface $snapshotStorage): Builder
    {
        $this->snapshotStorage = $snapshotStorage;
        return $this;
    }

    /**
     * @param BasicSnapshotRepositoryInterface $snapshotRepository
     * @return $this
     */
    public function withSnapshotRepository(BasicSnapshotRepositoryInterface $snapshotRepository): Builder
    {
        $this->snapshotRepository = $snapshotRepository;
        return $this;
    }

    /**
     * @param NormalizerInterface $normalizer
     * @return $this
     */
    public function withNormalizer(NormalizerInterface $normalizer): Builder
    {
        $this->normalizer = $normalizer;
        return $this;
    }

    /**
     * @param LoggerInterface $logger
     * @return $this
     */
    public function withLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * @param Options $options
     * @return $this
     */
    public function withOptions(Options $options)
    {
        $this->options = $options;
        return $this;
    }

    public function build(): EngineInterface
    {
        return new Engine(
            $this->getEventRepository(),
            $this->getSnapshotRepository(),
            $this->options,
            $this->logger
        );
    }

    private function getSnapshotRepository(): ?BasicSnapshotRepositoryInterface
    {
        if ($this->snapshotRepository) {
            return $this->snapshotRepository;
        }
        if ($this->snapshotStorage && $this->normalizer) {
            return new StreamBackedSnapshotRepository($this->snapshotStorage, $this->normalizer);
        }
        return null;
    }

    private function getEventRepository(): EventRepositoryInterface
    {
        if ($this->eventRepository) {
            return $this->eventRepository;
        }
        if ($this->eventStorage && $this->normalizer) {
            return new StreamBackedEventRepository($this->eventStorage, $this->normalizer);
        }
        $message = 'Event repository or backing stream storage with normalizer are required';
        throw new InvalidConfigurationException($message);
    }
}
