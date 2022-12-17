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
use App\Handler\MessageTrait;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Security("is_granted('ROLE_USER')")]
class RoomController extends AbstractController
{
    use MessageTrait;

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
                $this->sendSavedMessage($request, $this->translator);
                return $this->redirectToRoute('rooms');
            } else {
                $this->sendErrorMessage($request, $this->translator);
            }
        }

        return $this->render('private/rooms/action.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
