<?php

namespace App\Dto;

use JMS\Serializer\Annotation as Serializer;

final class PaymentDto
{

    #[Serializer\Type("integer")]
    private ?int $paymentMethodId = null;

    #[Serializer\Type("float")]
    private ?float $amount = null;

    public function getPaymentMethodId(): ?int
    {
        return $this->paymentMethodId;
    }

    public function setPaymentMethodId(?int $paymentMethodId): static
    {
        $this->paymentMethodId = $paymentMethodId;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }


}