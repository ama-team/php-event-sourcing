<?php

namespace AmaTeam\EventSourcing\API\Exception;

use AmaTeam\EventSourcing\API\ExceptionInterface;
use RuntimeException;

class UnstableEventException extends RuntimeException implements ExceptionInterface
{

}
