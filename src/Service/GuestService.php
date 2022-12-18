<?php

declare(strict_types=1);

namespace App\Service;

final class GuestService
{
    public function isOibValid(?string $oib): bool
    {
        if (strlen($oib) != 11) {
            return false;
        }

        if (!is_numeric($oib)) {
            return false;
        }

        $oibArray = array_map('intval', str_split($oib));

        $lastDigit = $oibArray[10];

        $oibArray[0] += 10;

        for ($i = 0; $i < 10; $i++) {
            if ($oibArray[$i] % 10 === 0) {
                $oibArray[$i] = 10;
            } else {
                $oibArray[$i] %= 10;
            }

            $oibArray[$i] *= 2;
            $oibArray[$i] %= 11;
            $oibArray[$i + 1] += $oibArray[$i];
        }

        if ($oibArray[9] === 1) {
            $controlDigit = 0;
        } else {
            $controlDigit = 11 - $oibArray[9];
        }

        if ($controlDigit === $lastDigit) {
            return true;
        }

        return false;
    }
}