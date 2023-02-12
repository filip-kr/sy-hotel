<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contract\Repository\GuestRepositoryInterface;
use App\Entity\Guest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class GuestRepository extends ServiceEntityRepository implements GuestRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guest::class);
    }
}
