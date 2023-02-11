<?php

declare(strict_types=1);

namespace App\DataPersister;

use App\Contract\DataPersister\RoomDataPersisterInterface;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;

final class RoomDataPersister implements RoomDataPersisterInterface
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
    public function create(): Room
    {
        return new Room();
    }

    /**
     * @inheritDoc
     */
    public function save(Room $room): void
    {
        $this->entityManager->persist($room);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function remove(Room $room): void
    {
        $this->entityManager->remove($room);
        $this->entityManager->flush();
    }
}
