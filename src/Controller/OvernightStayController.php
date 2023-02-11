<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\OvernightStayDataPersisterInterface;
use App\Entity\OvernightStay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OvernightStayRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OvernightStayForm;
use App\Service\ReceiptService;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class OvernightStayController extends AbstractController
{
    /**
     * @param OvernightStayRepository $repository
     * @param OvernightStayDataPersisterInterface $dataPersister
     * @param ReceiptService $receiptService
     */
    public function __construct(
        private OvernightStayRepository             $repository,
        private OvernightStayDataPersisterInterface $dataPersister,
        private ReceiptService                      $receiptService
    )
    {
    }

    /**
     * @return Response
     */
    #[Route('/overnightstays', name: 'overnightstays')]
    public function index(): Response
    {
        $overnightStays = $this->repository->findAll();

        return $this->render('private/overnightstays/view.html.twig', [
            'overnightStays' => $overnightStays
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/overnightstays/create', name: 'overnightstays-create')]
    public function create(Request $request): Response
    {
        $overnightStay = $this->dataPersister->create();

        $form = $this->createForm(
            OvernightStayForm::class,
            $overnightStay
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('overnightstays');
        }

        return $this->render('private/overnightstays/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param OvernightStay $overnightStay
     * @return Response
     */
    #[Route('/overnightstays/changestatus/{id}', name: 'overnightstays-changestatus')]
    public function changeStatus(OvernightStay $overnightStay): Response
    {
        $reverseStatus = !$overnightStay->isActive();
        $overnightStay->setIsActive($reverseStatus);
        $this->dataPersister->save($overnightStay);

        return $this->redirectToRoute('overnightstays');
    }

    /**
     * @param OvernightStay $overnightStay
     * @return Response
     */
    #[Route('/overnightstays/delete/{id}', name: 'overnightstays-delete')]
    public function delete(OvernightStay $overnightStay): Response
    {
        if ($overnightStay->isActive()) {
            return $this->redirectToRoute('overnightstays');
        }

        $this->dataPersister->remove($overnightStay);
        return $this->redirectToRoute('overnightstays');
    }

    /**
     * @param OvernightStay $overnightStay
     * @return void
     */
    #[Route('/overnightstays/print/{id}', name: 'overnightstays-print')]
    public function printReceipt(OvernightStay $overnightStay): void
    {
        if ($overnightStay->isActive()) {
            $overnightStay->setIsActive(false);
        }

        $this->dataPersister->save($overnightStay);
        $this->receiptService->generateReceipt($overnightStay);
    }
}
