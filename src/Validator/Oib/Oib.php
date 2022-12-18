<?php

declare(strict_types=1);

namespace App\Validator\Oib;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class Oib extends Constraint
{
    public string $message = 'Neispravan OIB';
}