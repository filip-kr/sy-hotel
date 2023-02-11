<?php

declare(strict_types=1);

namespace App\Contract\DataPersister;

use App\Entity\Room;

interface RoomDataPersisterInterface
{
    /**
     * @return Room
     */
    public function create(): Room;

    /**
     * @param Room $room
     * @return void
     */
    public function save(Room $room): void;

    /**
     * @param Room $room
     * @return void
     */
    public function remove(Room $room): void;
}