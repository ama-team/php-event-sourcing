<?php

namespace AmaTeam\EventSourcing\API\Misc;

class Identifier implements IdentifierInterface
{
    const STRING_TEMPLATE = '%s:%s';
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $type
     * @param string $id
     */
    public function __construct(string $type, string $id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return static::asString($this);
    }

    public static function asString(IdentifierInterface $id): string
    {
        return sprintf(static::STRING_TEMPLATE, $id->getType(), $id->getId());
    }
}
