<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\UserDataPersisterInterface;
use App\Contract\Repository\UserRepositoryInterface;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route(
    path: '/dashboard',
    name: 'dashboard_',
    requirements: ['id' => '\d+']
)]
#[IsGranted('ROLE_USER')]
class DashboardController extends AbstractController
{
    /**
     * @param UserRepositoryInterface $userRepository
     * @param UserDataPersisterInterface $userDataPersister
     * @param StatisticsService $statisticsService
     */
    public function __construct(
        private UserRepositoryInterface    $userRepository,
        private UserDataPersisterInterface $userDataPersister,
        private StatisticsService          $statisticsService
    )
    {
    }

    /**
     * @return Response
     */
    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        $users = $this->userRepository->findAll();
        $dataCount = $this->statisticsService->getDataCount();

        return $this->render('private/dashboard/view.html.twig', [
            'users' => $users,
            'dataCount' => $dataCount
        ]);
    }

    /**
     * @return Response
     */
    #[Route('/index/reservationdata', name: 'api_stats_data')]
    public function getSignInDates(): Response
    {
        return new Response(json_encode($this->statisticsService->getReservationMonths()));
    }

    /**
     * @param User $user
     * @param Request $request
     * @return Response
     */
    #[Route('/updateuser/{id}', name: 'update_user')]
    public function update(User $user, Request $request): Response
    {
        $form = $this->createForm(
            RegistrationFormType::class,
            $user
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->userDataPersister->getHashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $this->userDataPersister->save($form->getData());

            return $this->redirectToRoute('dashboard_index');
        }

        return $this->render('private/dashboard/registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @param User $user
     * @return Response
     */
    #[Route('/deleteuser/{id}', name: 'delete_user')]
    public function delete(User $user): Response
    {
        $this->userDataPersister->remove($user);
        return $this->redirectToRoute('dashboard_index');
    }
}
