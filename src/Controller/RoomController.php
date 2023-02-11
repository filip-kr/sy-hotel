<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\RoomDataPersisterInterface;
use App\DataPersister\RoomDataPersister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RoomForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Room;

#[Security("is_granted('ROLE_USER')")]
class RoomController extends AbstractController
{
    /**
     * @param RoomRepository $repository
     * @param RoomDataPersisterInterface $dataPersister
     */
    public function __construct(
        private RoomRepository             $repository,
        private RoomDataPersisterInterface $dataPersister
    )
    {
    }

    /**
     * @return Response
     */
    #[Route('/rooms', name: 'rooms')]
    public function index(): Response
    {
        $rooms = $this->repository->findAll();

        return $this->render('private/rooms/view.html.twig', [
            'rooms' => $rooms
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/rooms/create', name: 'rooms-create')]
    public function create(Request $request): Response
    {
        $room = $this->dataPersister->create();

        $form = $this->createForm(
            RoomForm::class,
            $room
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('rooms');
        }

        return $this->render('private/rooms/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/rooms/update/{id}', name: 'rooms-update')]
    public function update(Room $room, Request $request): Response
    {
        $form = $this->createForm(
            RoomForm::class,
            $room
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('rooms');
        }

        return $this->render('private/rooms/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/rooms/delete/{id}', name: 'rooms-delete')]
    public function delete(Room $room): Response
    {
        if ($room->getOvernightStays()) {
            foreach ($room->getOvernightStays() as $os) {
                if ($os->isActive()) {
                    return $this->redirectToRoute('rooms');
                }
            }
        }

        $this->dataPersister->remove($room);
        return $this->redirectToRoute('rooms');
    }
}
