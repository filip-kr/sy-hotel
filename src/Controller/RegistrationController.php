<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\UserDataPersisterInterface;
use App\Contract\Repository\UserRepositoryInterface;
use App\Form\RegistrationFormType;
use App\Service\EmailVerifierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    /**
     * @param UserRepositoryInterface $repository
     * @param UserDataPersisterInterface $dataPersister
     * @param EmailVerifierService $emailVerifier
     * @param TranslatorInterface $translator
     */
    public function __construct(
        private UserRepositoryInterface    $repository,
        private UserDataPersisterInterface $dataPersister,
        private EmailVerifierService       $emailVerifier,
        private TranslatorInterface        $translator
    )
    {
    }

    /**
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        $user = $this->dataPersister->create();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->dataPersister->getHashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->dataPersister->save($form->getData());

            $email = $this->emailVerifier->createEmailConfirmation($user->getEmail());
            $this->emailVerifier->sendEmailConfirmation(
                'verify_email',
                $user,
                $email
            );

            return $this->redirectToRoute('dashboard_index');
        }

        return $this->render('private/dashboard/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/verify/email', name: 'verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $id = $request->get('id');

        if (!$id) {
            return $this->redirectToRoute('register');
        }

        $user = $this->repository->find($id);

        if (!$user) {
            return $this->redirectToRoute('register');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $this->translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('register');
        }

        return $this->redirectToRoute('dashboard_index');
    }
}
