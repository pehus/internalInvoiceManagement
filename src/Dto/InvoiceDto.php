<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;

final class InvoiceDto
{
    #[Serializer\Type("string")]
    private ?string $clientName = null;

    #[Serializer\Type("array<App\Dto\InvoiceItemDto>")]
    private ?array $items = null;

    #[Serializer\Type("integer")]
    private ?int $statusId = null;

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function setStatusId(?int $statusId): static
    {
        $this->statusId = $statusId;

        return $this;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }

    public function setItems(?array $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function setClientName(?string $clientName): static
    {
        $this->clientName = $clientName;

        return $this;
    }
}
