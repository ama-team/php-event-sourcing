<?php

namespace AmaTeam\EventSourcing\API\Normalization;

interface NormalizerInterface
{
    public function normalize($structure): array;
    public function denormalize(array $data, string $type);
}
