<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataPersister\OvernightStayDataPersister;
use App\Entity\OvernightStay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Repository\OvernightStayRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OvernightStayForm;

#[Security("is_granted('ROLE_USER')")]
class OvernightStayController extends AbstractController
{
    #[Route('/overnightstays', name: 'overnightstays')]
    public function index(OvernightStayRepository $overnightStayRepository): Response
    {
        $overnightStays = $overnightStayRepository->findAll();

        return $this->render('private/overnightstays/view.html.twig', [
            'overnightStays' => $overnightStays
        ]);
    }

    #[Route('/overnightstays/create', name: 'overnightstays-create')]
    public function create(
        Request $request,
        OvernightStayDataPersister $osDataPersister
    ): Response 
    {
        $overnightStay = $osDataPersister->create();

        $form = $this->createForm(
            OvernightStayForm::class,
            $overnightStay
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $osDataPersister->save($form->getData());
                return $this->redirectToRoute('overnightstays');
            }
        }

        return $this->render('private/overnightstays/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/overnightstays/changestatus/{id}', name: 'overnightstays-changestatus')]
    public function changeStatus(
        OvernightStay $overnightStay,
        OvernightStayDataPersister $osDataPersister
    ): Response 
    {
        if ($overnightStay->isActive()) {
            $overnightStay->setIsActive(false);
        } else {
            $overnightStay->setIsActive(true);
        }

        $osDataPersister->save($overnightStay);

        return $this->redirectToRoute('overnightstays');
    }

    #[Route('/overnightstays/delete/{id}', name: 'overnightstays-delete')]
    public function delete(
        OvernightStay $overnightStay,
        OvernightStayDataPersister $osDataPersister
    ): Response 
    {
        if ($overnightStay->isActive()) {
            return $this->redirectToRoute('overnightstays');
        }

        $osDataPersister->remove($overnightStay);
        return $this->redirectToRoute('overnightstays');
    }
}
