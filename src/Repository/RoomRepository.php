<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contract\Repository\RoomRepositoryInterface;
use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class RoomRepository extends ServiceEntityRepository implements RoomRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * @inheritDoc
     */
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
