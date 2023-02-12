<?php

declare(strict_types=1);

namespace App\Contract\Repository;

interface OvernightStayRepositoryInterface
{
    /**
     * @return int
     */
    public function getActiveCount(): int;
}
