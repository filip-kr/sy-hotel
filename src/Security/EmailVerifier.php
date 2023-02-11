<?php

declare(strict_types=1);

namespace App\Security;

use App\Contract\DataPersister\UserDataPersisterInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    /**
     * @param VerifyEmailHelperInterface $verifyEmailHelper
     * @param MailerInterface $mailer
     * @param UserDataPersisterInterface $userDataPersister
     */
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface            $mailer,
        private UserDataPersisterInterface $userDataPersister
    )
    {
    }

    /**
     * @param string $verifyEmailRouteName
     * @param UserInterface $user
     * @param TemplatedEmail $email
     * @return void
     * @throws TransportExceptionInterface
     */
    public function sendEmailConfirmation(
        string         $verifyEmailRouteName,
        UserInterface  $user,
        TemplatedEmail $email
    ): void
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string)$user->getId(),
            $user->getEmail(),
            ['id' => (string)$user->getId()]
        );

        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);
    }


    /**
     * @param Request $request
     * @param UserInterface $user
     * @return void
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(
        Request       $request,
        UserInterface $user
    ): void
    {
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), (string)$user->getId(), $user->getEmail());

        $user->setIsVerified(true);
        $this->userDataPersister->save($user);
    }
}
