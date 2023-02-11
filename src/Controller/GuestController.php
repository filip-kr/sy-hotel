<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\GuestDataPersisterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GuestRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Guest;
use App\Form\GuestFormType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class GuestController extends AbstractController
{
    /**
     * @param GuestRepository $repository
     * @param GuestDataPersisterInterface $dataPersister
     */
    public function __construct(
        private GuestRepository             $repository,
        private GuestDataPersisterInterface $dataPersister
    )
    {
    }

    /**
     * @return Response
     */
    #[Route('/guests', name: 'guests')]
    public function index(): Response
    {
        $guests = $this->repository->findAll();

        return $this->render('private/guests/view.html.twig', [
            'guests' => $guests
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/guests/create', name: 'guests-create')]
    public function create(Request $request): Response
    {
        $guest = $this->dataPersister->create();

        $form = $this->createForm(
            GuestFormType::class,
            $guest
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('guests');
        }

        return $this->render('private/guests/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Guest $guest
     * @param Request $request
     * @return Response
     */
    #[Route('/guests/update/{id}', name: 'guests-update')]
    public function update(Guest $guest, Request $request): Response
    {
        $form = $this->createForm(
            GuestFormType::class,
            $guest
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('guests');
        }

        return $this->render('private/guests/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Guest $guest
     * @return Response
     */
    #[Route('/guests/delete/{id}', name: 'guests-delete')]
    public function delete(Guest $guest): Response
    {
        if ($guest->getReservations()) {
            return $this->redirectToRoute('guests');
        }

        $this->dataPersister->remove($guest);
        return $this->redirectToRoute('guests');
    }
}
