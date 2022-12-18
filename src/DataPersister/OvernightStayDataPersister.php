<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Entity\OvernightStay;
use Doctrine\ORM\EntityManagerInterface;

final class OvernightStayDataPersister
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) 
    {
        $this->entityManager = $entityManager;
    }

    public function create(): OvernightStay
    {
        return new OvernightStay();
    }

    public function save(OvernightStay $overnightStay): void
    {
        $this->entityManager->persist($overnightStay);
        $this->entityManager->flush();
    }

    public function remove(OvernightStay $overnightStay): void
    {
        $this->entityManager->remove($overnightStay);
        $this->entityManager->flush();
    }
}
