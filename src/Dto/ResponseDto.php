<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;

final class ResponseDto
{
    #[Serializer\Type("string")]
    private ?string $html = null;

    private mixed $data = null;

    #[Serializer\Type("string")]
    private string $message;

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function setHtml(?string $html): static
    {
        $this->html = $html;

        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }


}