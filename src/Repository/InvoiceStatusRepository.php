<?php

namespace App\Repository;

use App\Entity\InvoiceStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class InvoiceStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InvoiceStatus::class);
    }

    public function findByName(string $name): ?InvoiceStatus
    {
        return $this->findOneBy(['name' => $name]);
    }
}
