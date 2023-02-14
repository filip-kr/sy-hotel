<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\OvernightStayDataPersisterInterface;
use App\Contract\Repository\OvernightStayRepositoryInterface;
use App\Entity\OvernightStay;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OvernightStayFormType;
use App\Service\ReceiptService;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route(
    path: '/overnightstay',
    name: 'overnightstay_',
    requirements: ['id' => '\d+']
)]
#[IsGranted('ROLE_USER')]
class OvernightStayController extends AbstractController
{
    /**
     * @param OvernightStayRepositoryInterface $repository
     * @param OvernightStayDataPersisterInterface $dataPersister
     * @param ReceiptService $receiptService
     */
    public function __construct(
        private OvernightStayRepositoryInterface    $repository,
        private OvernightStayDataPersisterInterface $dataPersister,
        private ReceiptService                      $receiptService
    )
    {
    }

    /**
     * @return Response
     */
    #[Route('/index', name: 'index')]
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
    #[Route('/create', name: 'create')]
    public function create(Request $request): Response
    {
        $overnightStay = $this->dataPersister->create();

        $form = $this->createForm(
            OvernightStayFormType::class,
            $overnightStay
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataPersister->save($form->getData());

            return $this->redirectToRoute('overnightstay_index');
        }

        return $this->render('private/overnightstays/action.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param OvernightStay $overnightStay
     * @return Response
     */
    #[Route('/changestatus/{id}', name: 'changestatus')]
    public function changeStatus(OvernightStay $overnightStay): Response
    {
        $reverseStatus = !$overnightStay->isActive();
        $overnightStay->setIsActive($reverseStatus);
        $this->dataPersister->save($overnightStay);

        return $this->redirectToRoute('overnightstay_index');
    }

    /**
     * @param OvernightStay $overnightStay
     * @return Response
     */
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(OvernightStay $overnightStay): Response
    {
        if ($overnightStay->isActive()) {
            return $this->redirectToRoute('overnightstay_index');
        }

        $this->dataPersister->remove($overnightStay);
        return $this->redirectToRoute('overnightstay_index');
    }

    /**
     * @param OvernightStay $overnightStay
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    #[Route('/print/{id}', name: 'print_receipt')]
    public function printReceipt(OvernightStay $overnightStay): void
    {
        if ($overnightStay->isActive()) {
            $overnightStay->setIsActive(false);
        }

        $this->dataPersister->save($overnightStay);
        $this->receiptService->generateReceipt($overnightStay);
    }
}
