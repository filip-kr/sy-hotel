<?php

declare(strict_types=1);

namespace App\Contract\Repository;

interface GuestRepositoryInterface
{
    /**
     * @return int
     */
    public function getCount(): int;
}
