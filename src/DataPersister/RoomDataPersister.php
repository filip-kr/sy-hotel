<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;

final class RoomDataPersister
{
    public function __construct(
        private EntityManagerInterface $entityManager, 
    )
    {
        $this->entityManager = $entityManager;
    }

    public function create(): Room
    {
        return new Room();
    }

    public function save(Room $room): void
    {
        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    public function remove(Room $room): void
    {
        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }
}