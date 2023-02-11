<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Contract\DataPersister\OvernightStayDataPersisterInterface;
use App\Entity\OvernightStay;
use Doctrine\ORM\EntityManagerInterface;

final class OvernightStayDataPersister implements OvernightStayDataPersisterInterface
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @inheritDoc
     */
    public function create(): OvernightStay
    {
        return new OvernightStay();
    }

    /**
     * @inheritDoc
     */
    public function save(OvernightStay $overnightStay): void
    {
        $this->entityManager->persist($overnightStay);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove(OvernightStay $overnightStay): void
    {
        $this->entityManager->remove($overnightStay);
        $this->entityManager->flush();
    }
}
