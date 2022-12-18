<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Repository\ReservationRepository;

#[Security("is_granted('ROLE_USER')")]
class ReservationController extends AbstractController
{
    #[Route('/reservations', name: 'reservations')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();

        return $this->render('private/reservations/view.html.twig', [
            'reservations' => $reservations
        ]);
    }
}
