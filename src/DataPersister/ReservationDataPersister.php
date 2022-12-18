<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;

final class ReservationDataPersister
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) 
    {
        $this->entityManager = $entityManager;
    }

    public function create(): Reservation
    {
        return new Reservation();
    }

    public function save(Reservation $reservation): void
    {
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();
    }

    public function remove(Reservation $reservation): void
    {
        $this->entityManager->remove($reservation);
        $this->entityManager->flush();
    }
}
