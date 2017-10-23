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
     * @var DateTimeInterface
     */
    private $acknowledgedAt;
    /**
     * @var DateTimeInterface|null
     */
    private $occurredAt;
    /**
     * @var DateTimeInterface|null
     */
    private $createdAt;

    /**
     * @param IdentifierInterface $stream
     * @param int $index
     * @param int $version
     * @param string $type
     * @param array $data
     * @param DateTimeInterface|null $occurredAt
     * @param DateTimeInterface $acknowledgedAt
     * @param DateTimeInterface $createdAt
     */
    public function __construct( // NOSONAR
        IdentifierInterface $stream,
        int $index,
        int $version,
        array $data,
        DateTimeInterface $acknowledgedAt,
        DateTimeInterface $occurredAt = null,
        DateTimeInterface $createdAt = null,
        string $type = null
    ) {
        $this->stream = $stream;
        $this->index = $index;
        $this->version = $version;
        $this->type = $type;
        $this->data = $data;
        $this->occurredAt = $occurredAt;
        $this->acknowledgedAt = $acknowledgedAt;
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
     * @return DateTimeInterface|null
     */
    public function getOccurredAt(): ?DateTimeInterface
    {
        return $this->occurredAt;
    }

    /**
     * @return DateTimeInterface
     */
    public function getAcknowledgedAt(): DateTimeInterface
    {
        return $this->acknowledgedAt;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }
}
