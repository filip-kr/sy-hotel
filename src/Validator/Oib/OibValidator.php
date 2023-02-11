<?php

declare(strict_types=1);

namespace App\Validator\Oib;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use App\Service\OibService;

class OibValidator extends ConstraintValidator
{
    /**
     * @param OibService $oibService
     */
    public function __construct(private OibService $oibService)
    {
    }

    /**
     * @param $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Oib) {
            throw new UnexpectedTypeException($constraint, Oib::class);
        }

        if (!$value || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->oibService->isOibValid($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
