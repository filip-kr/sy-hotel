<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Repository\OvernightStayRepository;

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
}
