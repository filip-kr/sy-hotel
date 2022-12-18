<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Entity\Guest;
use Doctrine\ORM\EntityManagerInterface;

final class GuestDataPersister
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) 
    {
        $this->entityManager = $entityManager;
    }

    public function create(): Guest
    {
        return new Guest();
    }

    public function save(Guest $guest): void
    {
        $this->entityManager->persist($guest);
        $this->entityManager->flush();
    }

    public function remove(Guest $guest): void
    {
        $this->entityManager->remove($guest);
        $this->entityManager->flush();
    }
}
