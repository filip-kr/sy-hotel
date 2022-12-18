<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataPersister\RoomDataPersister;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RoomRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RoomForm;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Room;

#[Security("is_granted('ROLE_USER')")]
class RoomController extends AbstractController
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/rooms', name: 'rooms')]
    public function index(RoomRepository $roomRepository): Response
    {
        $rooms = $roomRepository->findAll();

        return $this->render('private/rooms/view.html.twig', [
            'rooms' => $rooms
        ]);
    }

    #[Route('/rooms/create', name: 'rooms-create')]
    public function create(
        Request $request,
        RoomDataPersister $roomDataPersister
    ): Response 
    {
        $room = $roomDataPersister->create();

        $form = $this->createForm(
            RoomForm::class,
            $room
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $roomDataPersister->save($form->getData());
                return $this->redirectToRoute('rooms');
            }
        }

        return $this->render('private/rooms/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/rooms/update/{id}', name: 'rooms-update')]
    public function update(
        Room $room,
        Request $request,
        RoomDataPersister $roomDataPersister
    ): Response 
    {
        $form = $this->createForm(
            RoomForm::class,
            $room
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $roomDataPersister->save($form->getData());
                return $this->redirectToRoute('rooms');
            }
        }

        return $this->render('private/rooms/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/rooms/delete/{id}', name: 'rooms-delete')]
    public function delete(
        Room $room,
        RoomDataPersister $roomDataPersister
    ): Response 
    {
        $roomDataPersister->remove($room);
        return $this->redirectToRoute('rooms');

        return $this->redirectToRoute('rooms');
    }

}
