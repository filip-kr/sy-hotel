<?php

declare(strict_types=1);

namespace App\Controller;

use App\Contract\DataPersister\UserDataPersisterInterface;
use App\Form\RegistrationForm;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    /**
     * @param UserRepository $repository
     * @param UserDataPersisterInterface $dataPersister
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EmailVerifier $emailVerifier
     * @param TranslatorInterface $translator
     */
    public function __construct(
        private UserRepository              $repository,
        private UserDataPersisterInterface  $dataPersister,
        private UserPasswordHasherInterface $passwordHasher,
        private EmailVerifier               $emailVerifier,
        private TranslatorInterface         $translator
    )
    {
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        $user = $this->dataPersister->create();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $this->dataPersister->save($form->getData());

            $this->emailVerifier->sendEmailConfirmation(
                'verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('sy@hotel.com', 'Symfony Hotel'))
                    ->to($user->getEmail())
                    ->subject('Potvrda e-mail adrese')
                    ->htmlTemplate('private/dashboard/registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('private/dashboard/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

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

        return $this->redirectToRoute('dashboard');
    }
}
