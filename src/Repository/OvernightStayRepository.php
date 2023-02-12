<?php

declare(strict_types=1);

namespace App\Repository;

use App\Contract\Repository\OvernightStayRepositoryInterface;
use App\Entity\OvernightStay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OvernightStayRepository extends ServiceEntityRepository implements OvernightStayRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OvernightStay::class);
    }
}
