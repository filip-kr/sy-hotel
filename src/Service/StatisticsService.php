<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Repository\GuestRepositoryInterface;
use App\Contract\Repository\OvernightStayRepositoryInterface;
use App\Contract\Repository\ReservationRepositoryInterface;
use App\Contract\Repository\RoomRepositoryInterface;

final class StatisticsService
{

    /**
     * @param GuestRepositoryInterface $guestRepository
     * @param OvernightStayRepositoryInterface $overnightStayRepository
     * @param ReservationRepositoryInterface $reservationRepository
     * @param RoomRepositoryInterface $roomRepository
     */
    public function __construct(
        private GuestRepositoryInterface         $guestRepository,
        private OvernightStayRepositoryInterface $overnightStayRepository,
        private ReservationRepositoryInterface   $reservationRepository,
        private RoomRepositoryInterface          $roomRepository
    )
    {
    }

    /**
     * @return array
     */
    public function getDataCount(): array
    {
        return [
            'guestCount' => $this->guestRepository->getCount(),
            'reservationCount' => $this->reservationRepository->getCount(),
            'activeStayCount' => $this->overnightStayRepository->getActiveCount(),
            'availableRoomCount' => $this->roomRepository->getAvailableCount()
        ];
    }

    /**
     * @return array
     */
    public function getReservationMonths(): array
    {
        $reservations = $this->reservationRepository->findAll();
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = 0;
        }

        foreach ($reservations as $reservation) {
            $months[(int)date_format($reservation->getSignInDate(), 'm')]++;
        }

        return $months;
    }
}
