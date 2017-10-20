<?php

namespace AmaTeam\Bundle\EventSourcingBundle;

use AmaTeam\Bundle\EventSourcingBundle\API\IdentifierInterface;

class Identifier implements IdentifierInterface
{
    /**
     * @var string
     */
    private $type;
    /**
     * @var string
     */
    private $id;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }
}
