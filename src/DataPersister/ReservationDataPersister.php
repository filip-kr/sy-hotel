<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Contract\DataPersister\ReservationDataPersisterInterface;
use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;

final class ReservationDataPersister implements ReservationDataPersisterInterface
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
    public function create(): Reservation
    {
        return new Reservation();
    }

    /**
     * @inheritDoc
     */
    public function save(Reservation $reservation): void
    {
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove(Reservation $reservation): void
    {
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();
    }
}
