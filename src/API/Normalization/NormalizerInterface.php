<?php

namespace AmaTeam\EventSourcing\API\Normalization;

interface NormalizerInterface
{
    public function normalize($value): array;
    public function denormalize(array $data, string $type);
}
