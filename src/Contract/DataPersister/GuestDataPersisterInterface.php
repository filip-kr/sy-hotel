<?php

declare(strict_types=1);

namespace App\Contract\DataPersister;

use App\Entity\Guest;

interface GuestDataPersisterInterface
{
    /**
     * @return Guest
     */
    public function create(): Guest;


    /**
     * @param Guest $guest
     * @return void
     */
    public function save(Guest $guest): void;

    /**
     * @param Guest $guest
     * @return void
     */
    public function remove(Guest $guest): void;
}