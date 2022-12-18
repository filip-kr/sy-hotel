<?php

declare(strict_types=1);

namespace App\Validator\Oib;

use App\Validator\Oib\Oib;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use App\Service\GuestService;

class OibValidator extends ConstraintValidator
{
    public function __construct(GuestService $guestService)
    {
        $this->guestService = $guestService;
    }

    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Oib) {
            throw new UnexpectedTypeException($constraint, Oib::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->guestService->isOibValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
