<?php

namespace AmaTeam\EventSourcing\API\Event;

use AmaTeam\EventSourcing\API\Exception\RuntimeException;

class EventVersion implements EventVersionInterface
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var int
     */
    private $version;

    /**
     * @param string $type
     * @param int $version
     */
    public function __construct(string $type, int $version)
    {
        $this->type = $type;
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    public function validate(EventVersionInterface $version): void
    {
        if (empty($version->getType())) {
            throw new RuntimeException('Empty version type received');
        }
    }
}
