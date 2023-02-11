<?php

declare(strict_types=1);

namespace App\Service;

use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class EmailVerifierService extends EmailVerifier
{
    /**
     * @param string $to
     * @return TemplatedEmail
     */
    public function createEmailConfirmation(string $to): TemplatedEmail
    {
        $email = new TemplatedEmail();
        $email->from(new Address('sy@hotel.com', 'Symfony Hotel'));
        $email->to($to);
        $email->subject('Potvrda e-mail adrese');
        $email->htmlTemplate('private/dashboard/registration/confirmation_email.html.twig');

        return $email;
    }
}
