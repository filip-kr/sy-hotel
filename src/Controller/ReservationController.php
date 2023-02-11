<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\ReservationDataPersisterInterface;
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
    public function __construct(
        private ReservationRepository             $repository,
        private ReservationDataPersisterInterface $dataPersister
    )
    {
    }

    /**
     * @return Response
     */
    #[Route('/reservations', name: 'reservations')]
    public function index(): Response
    {
        $reservations = $this->repository->findAll();

        return $this->render('private/reservations/view.html.twig', [
            'reservations' => $reservations
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/reservations/create', name: 'reservations-create')]
    public function create(Request $request): Response
    {
        $reservation = $this->dataPersister->create();

        $form = $this->createForm(
            ReservationForm::class,
            $reservation
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());
            return $this->redirectToRoute('reservations');

        }

        return $this->render('private/reservations/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/reservations/update/{id}', name: 'reservations-update')]
    public function update(Reservation $reservation, Request $request,): Response
    {
        $form = $this->createForm(
            ReservationForm::class,
            $reservation
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());
            return $this->redirectToRoute('reservations');
        }

        return $this->render('private/reservations/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Reservation $reservation
     * @return Response
     */
    #[Route('/reservations/delete/{id}', name: 'reservations-delete')]
    public function delete(Reservation $reservation): Response
    {
        if ($reservation->getOvernightStay() && $reservation->getOvernightStay()->isActive()) {
            return $this->redirectToRoute('reservations');
        }

        $this->dataPersister->remove($reservation);
        return $this->redirectToRoute('reservations');
    }
}
