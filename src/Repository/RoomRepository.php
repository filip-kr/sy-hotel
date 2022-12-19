<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function getAvailable(): array
    {
        $query = $this->createQueryBuilder('r')
            ->select('r')
            ->andWhere('

            r NOT IN (SELECT IDENTITY(os.room) 
            FROM App\Entity\OvernightStay os 
            WHERE os.isActive = true)

        ');

        return $query->getQuery()
            ->getResult();
    }
}
