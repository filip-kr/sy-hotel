<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GuestRepository;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use App\DataPersister\GuestDataPersister;
use App\Form\GuestForm;

#[Security("is_granted('ROLE_USER')")]
class GuestController extends AbstractController
{
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    #[Route('/guests', name: 'guests')]
    public function index(GuestRepository $guestRepository): Response
    {
        $guests = $guestRepository->findAll();

        return $this->render('private/guests/view.html.twig', [
            'guests' => $guests
        ]);
    }

    #[Route('/guests/create', name: 'guests-create')]
    public function create(
        Request $request,
        GuestDataPersister $guestDataPersister
    ): Response 
    {
        $guest = $guestDataPersister->create();

        $form = $this->createForm(
            GuestForm::class,
            $guest
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $guestDataPersister->save($form->getData());
                return $this->redirectToRoute('guests');
            }
        }

        return $this->render('private/guests/action.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
