<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contract\Repository\ReservationRepositoryInterface;
use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ReservationRepository extends ServiceEntityRepository implements ReservationRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        $query = $this->createQueryBuilder('r');
        $query->select('COUNT(r.id)');

        return $query->getQuery()->getSingleScalarResult();
    }
}
