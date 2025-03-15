<?php

namespace App\Service;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;

class SerializerService
{
    private SerializerInterface $serializer;

    public function __construct()
    {
        $this->serializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy(
                new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy())
            )
            ->build();
    }

    public function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }
}