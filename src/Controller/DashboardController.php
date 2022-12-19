<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(StatisticsService $statisticsService): Response
    {
        $dataCount = $statisticsService->getDataCount();

        return $this->render('private/dashboard/view.html.twig', [
            'dataCount' => $dataCount
        ]);
    }
}
