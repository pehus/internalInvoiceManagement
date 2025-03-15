<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $clientName;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceItem::class, cascade: ['persist', 'remove'])]
    private Collection $items;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $totalAmount = 0.0;

    #[ORM\ManyToOne(targetEntity: InvoiceStatus::class)]
    #[ORM\JoinColumn(nullable: false)]
    private InvoiceStatus $status;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: Payment::class, cascade: ['persist', 'remove'])]
    private Collection $payments;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    public function updateStatus(): static
    {
        $totalPaid = array_sum(array_map(fn(Payment $payment) => $payment->getAmount(), $this->payments->toArray()));

        if ($totalPaid >= $this->totalAmount)
        {
            $this->status = new InvoiceStatus('zaplacená');
        }

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): static
    {
        $this->clientName = $clientName;

        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItems(Collection $items): static
    {
        $this->items = $items;

        return $this;
    }

    public function getTotalAmount(): float
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(float $totalAmount): static
    {
        $this->totalAmount = $totalAmount;

        return $this;
    }

    public function getStatus(): InvoiceStatus
    {
        return $this->status;
    }

    public function setStatus(InvoiceStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function setPayments(Collection $payments): static
    {
        $this->payments = $payments;

        return $this;
    }
}