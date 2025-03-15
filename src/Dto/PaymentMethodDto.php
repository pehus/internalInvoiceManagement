<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;

final class PaymentMethodDto
{
    #[Serializer\Type("integer")]
    private int $id;

    #[Serializer\Type("string")]
    private string $name;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(int $name): static
    {
        $this->name = $name;

        return $this;
    }
}