<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\GuestRepository;
use App\Repository\OvernightStayRepository;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;

final class StatisticsService
{
    /**
     * @param GuestRepository $guestRepository
     * @param OvernightStayRepository $overnightStayRepository
     * @param ReservationRepository $reservationRepository
     * @param RoomRepository $roomRepository
     */
    public function __construct(
        private GuestRepository         $guestRepository,
        private OvernightStayRepository $overnightStayRepository,
        private ReservationRepository   $reservationRepository,
        private RoomRepository          $roomRepository
    )
    {
    }

    /**
     * @return array
     */
    public function getDataCount(): array
    {
        return [
            'guestCount' => count($this->guestRepository->findAll()),
            'reservationCount' => count($this->reservationRepository->findAll()),
            'activeStayCount' => count($this->overnightStayRepository->findBy(['isActive' => true])),
            'availableRoomCount' => count($this->roomRepository->getAvailable())
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
