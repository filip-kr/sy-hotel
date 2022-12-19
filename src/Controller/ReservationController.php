<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataPersister\ReservationDataPersister;
use App\Entity\Reservation;
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

    #[Route('/reservations/update/{id}', name: 'reservations-update')]
    public function update(
        Reservation $reservation,
        Request $request,
        ReservationDataPersister $reservationDataPersister
    ): Response 
    {
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

    #[Route('/reservations/delete/{id}', name: 'reservations-delete')]
    public function delete(
        Reservation $reservation,
        ReservationDataPersister $reservationDataPersister
    ): Response 
    {
        $reservationDataPersister->remove($reservation);
        return $this->redirectToRoute('reservations');
    }
}
