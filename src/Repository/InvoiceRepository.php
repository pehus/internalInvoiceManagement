<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\InvoiceStatus;
use App\enum\InvoiceStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function findByName(string $name): ?InvoiceStatus
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function updateStatus(Invoice $invoice): void
    {
        $entityManager = $this->getEntityManager();
        $statusRepo = $entityManager->getRepository(InvoiceStatus::class);

        $paidAmount = array_sum(array_map(fn($p) => $p->getAmount(), $invoice->getPayments()->toArray()));

        if ($paidAmount >= $invoice->getTotalAmount()) {
            $invoice->setStatus($statusRepo->find(InvoiceStatusEnum::PAID));
        }

        $entityManager->flush();
    }
}
