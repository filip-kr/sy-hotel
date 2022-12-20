<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\GuestRepository;
use App\Repository\OvernightStayRepository;
use App\Repository\ReservationRepository;
use App\Repository\RoomRepository;

final class StatisticsService
{
    public function __construct(
        GuestRepository $guestRepository,
        OvernightStayRepository $overnightStayRepository,
        ReservationRepository $reservationRepository,
        RoomRepository $roomRepository
    ) 
    {
        $this->guestRepository = $guestRepository;
        $this->overnightStayRepository = $overnightStayRepository;
        $this->reservationRepository = $reservationRepository;
        $this->roomRepository = $roomRepository;
    }

    public function getDataCount(): array
    {
        return $dataCount = [
            'guestCount' => count($this->guestRepository->findAll()),
            'reservationCount' => count($this->reservationRepository->findAll()),
            'activeStayCount' => count($this->overnightStayRepository->findBy(['isActive' => true])),
            'availableRoomCount' => count($this->roomRepository->getAvailable())
        ];
    }

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
