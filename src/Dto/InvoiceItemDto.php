<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;

final class InvoiceItemDto
{
    #[Serializer\Type("string")]
    private ?string $name;

    #[Serializer\Type("integer")]
    private ?int $quantity;

    #[Serializer\Type("float")]
    private ?float $unitPrice;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(?float $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }
}