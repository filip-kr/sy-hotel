<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataPersister\ReservationDataPersister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ReservationForm;

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

    #[Route('/reservations/create', name: 'reservations-create')]
    public function create(
        Request $request,
        ReservationDataPersister $reservationDataPersister
    ): Response 
    {
        $reservation = $reservationDataPersister->create();

        $form = $this->createForm(
            ReservationForm::class,
            $reservation
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $reservationDataPersister->save($form->getData());
                return $this->redirectToRoute('reservations');
            }
        }

        return $this->render('private/reservations/action.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
