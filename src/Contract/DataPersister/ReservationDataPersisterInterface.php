<?php

namespace App\Contract\DataPersister;

use App\Entity\Reservation;

interface ReservationDataPersisterInterface
{
    /**
     * @return Reservation
     */
    public function create(): Reservation;

    /**
     * @param Reservation $reservation
     * @return void
     */
    public function save(Reservation $reservation): void;

    /**
     * @param Reservation $reservation
     * @return void
     */
    public function remove(Reservation $reservation): void;
}
