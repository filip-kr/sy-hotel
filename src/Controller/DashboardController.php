<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Security("is_granted('ROLE_USER')")]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        StatisticsService $statisticsService,
        UserRepository $userRepository
    ): Response 
    {
        $users = $userRepository->findAll();
        $dataCount = $statisticsService->getDataCount();

        return $this->render('private/dashboard/view.html.twig', [
            'users' => $users,
            'dataCount' => $dataCount
        ]);
    }

    #[Route('/dashboard/reservationdata', name: 'dashboard-resdata')]
    public function getSignInDates(StatisticsService $statisticsService): Response
    {
        return new Response(json_encode($statisticsService->getReservationMonths()));
    }
}
