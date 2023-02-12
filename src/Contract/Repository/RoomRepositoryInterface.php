<?php

declare(strict_types=1);

namespace App\Contract\Repository;

interface RoomRepositoryInterface
{
    /**
     * @return int
     */
    public function getAvailableCount(): int;
}
