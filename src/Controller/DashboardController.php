<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataPersister\UserDataPersister;
use App\Entity\User;
use App\Form\RegistrationForm;
use App\Repository\UserRepository;
use App\Service\StatisticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

    #[Route('/dashboard/updateuser/{id}', name: 'dashboard-userupdate')]
    public function update(
        User $user,
        Request $request,
        UserDataPersister $userDataPersister,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response 
    {
        $form = $this->createForm(
            RegistrationForm::class,
            $user
        );
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $userDataPersister->save($form->getData());
                return $this->redirectToRoute('dashboard');
            }
        }

        return $this->render('private/dashboard/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/deleteuser/{id}', name: 'dashboard-userdelete')]
    public function delete(
        User $user,
        UserDataPersister $userDataPersister
    ): Response 
    {
        $userDataPersister->remove($user);
        return $this->redirectToRoute('dashboard');
    }
}
