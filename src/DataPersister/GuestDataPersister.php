<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Contract\DataPersister\GuestDataPersisterInterface;
use App\Entity\Guest;
use Doctrine\ORM\EntityManagerInterface;

final class GuestDataPersister implements GuestDataPersisterInterface
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
    public function create(): Guest
    {
        return new Guest();
    }

    /**
     * @inheritDoc
     */
    public function save(Guest $guest): void
    {
        $this->entityManager->persist($guest);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove(Guest $guest): void
    {
        $this->entityManager->remove($guest);
        $this->entityManager->flush();
    }
}
