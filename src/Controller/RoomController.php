<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\RoomDataPersisterInterface;
use App\Contract\Repository\RoomRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RoomFormType;
use App\Entity\Room;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    path: '/room',
    name: 'room_',
    requirements: ['id' => '\d+']
)]
#[IsGranted('ROLE_USER')]
class RoomController extends AbstractController
{
    /**
     * @param RoomRepositoryInterface $repository
     * @param RoomDataPersisterInterface $dataPersister
     */
    public function __construct(
        private RoomRepositoryInterface    $repository,
        private RoomDataPersisterInterface $dataPersister
    )
    {
    }

    /**
     * @return Response
     */
    #[Route('/index', name: 'index')]
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
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $room = $this->dataPersister->create();

        $form = $this->createForm(
            RoomFormType::class,
            $room
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('room_index');
        }

        return $this->render('private/rooms/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Room $room
     * @param Request $request
     * @return Response
     */
    #[Route('/update/{id}', name: 'update')]
    public function update(Room $room, Request $request): Response
    {
        $form = $this->createForm(
            RoomFormType::class,
            $room
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('room_index');
        }

        return $this->render('private/rooms/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Room $room
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Room $room): Response
    {
        if ($room->getOvernightStays()) {
            foreach ($room->getOvernightStays() as $os) {
                if ($os->isActive()) {
                    return $this->redirectToRoute('room_index');
                }
            }
        }

        $this->dataPersister->remove($room);
        return $this->redirectToRoute('room_index');
    }
}
