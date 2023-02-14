<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\GuestDataPersisterInterface;
use App\Contract\Repository\GuestRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Guest;
use App\Form\GuestFormType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    path: '/guest',
    name: 'guest_',
    requirements: ['id' => '\d+']
)]
#[IsGranted('ROLE_USER')]
class GuestController extends AbstractController
{
    /**
     * @param GuestRepositoryInterface $repository
     * @param GuestDataPersisterInterface $dataPersister
     */
    public function __construct(
        private GuestRepositoryInterface    $repository,
        private GuestDataPersisterInterface $dataPersister
    )
    {
    }

    /**
     * @return Response
     */
    #[Route('/index', name: 'index')]
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
    #[Route('/create', name: 'create')]
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

            return $this->redirectToRoute('guest_index');
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
    #[Route('/update/{id}', name: 'update')]
    public function update(Guest $guest, Request $request): Response
    {
        $form = $this->createForm(
            GuestFormType::class,
            $guest
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('guest_index');
        }

        return $this->render('private/guests/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Guest $guest
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Guest $guest): Response
    {
        if ($guest->getReservations()) {
            return $this->redirectToRoute('guest_index');
        }

        $this->dataPersister->remove($guest);
        return $this->redirectToRoute('guest_index');
    }
}
