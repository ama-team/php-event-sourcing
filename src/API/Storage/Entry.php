<?php

namespace AmaTeam\EventSourcing\API\Storage;

use AmaTeam\EventSourcing\API\Misc\IdentifierInterface;
use DateTimeInterface;

class Entry implements EntryInterface
{
    /**
     * @var IdentifierInterface
     */
    private $stream;
    /**
     * @var int
     */
    private $index;
    /**
     * @var int
     */
    private $version;
    /**
     * @var string
     */
    private $type;
    /**
     * @var array
     */
    private $data;
    /**
     * @var array
     */
    private $metadata;
    /**
     * @var DateTimeInterface|null
     */
    private $changedAt;
    /**
     * @var DateTimeInterface
     */
    private $recordedAt;

    /**
     * @param IdentifierInterface $stream
     * @param int $index
     * @param int $version
     * @param string $type
     * @param array $data
     * @param array $metadata
     * @param DateTimeInterface|null $changedAt
     * @param DateTimeInterface $recordedAt
     */
    public function __construct( // NOSONAR
        IdentifierInterface $stream,
        int $index,
        int $version,
        array $data,
        DateTimeInterface $recordedAt,
        DateTimeInterface $changedAt = null,
        string $type = null,
        array $metadata = []
    ) {
        $this->stream = $stream;
        $this->index = $index;
        $this->version = $version;
        $this->type = $type;
        $this->data = $data;
        $this->metadata = $metadata;
        $this->changedAt = $changedAt;
        $this->recordedAt = $recordedAt;
    }

    /**
     * @return IdentifierInterface
     */
    public function getStream(): IdentifierInterface
    {
        return $this->stream;
    }

    /**
     * @return int
     */
    public function getIndex(): int
    {
        return $this->index;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getChangedAt(): ?DateTimeInterface
    {
        return $this->changedAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getRecordedAt(): DateTimeInterface
    {
        return $this->recordedAt;
    }
}
