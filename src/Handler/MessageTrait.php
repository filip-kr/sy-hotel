<?php

declare(strict_types=1);

namespace App\Handler;

trait MessageTrait
{
    protected function sendSavedMessage($request,$translator): void
    {
        $session = $request->getSession();
        $session->getFlashBag()->add('successMessage', $translator->trans('Uspješno spremljeno'));
    }

    protected function sendErrorMessage($request,$translator): void
    {
        $session = $request->getSession();
        $session->getFlashBag()->add('errorMessage', $translator->trans('Dogodila se pogreška'));
    }

    protected function sendCustomMessage($request,$translator,$type, $messageKey): void
    {
        $session = $request->getSession();
        $session->getFlashBag()->add($type, $translator->trans($messageKey));
    }
}