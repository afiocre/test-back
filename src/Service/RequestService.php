<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class RequestService
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function hydrate(mixed $requestContent, string $dtoClass): mixed
    {
        return $this->serializer->deserialize($requestContent, $dtoClass, JsonEncoder::FORMAT);
    }
}
