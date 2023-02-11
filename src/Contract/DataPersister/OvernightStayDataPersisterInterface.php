<?php

declare(strict_types=1);

namespace App\Contract\DataPersister;

use App\Entity\OvernightStay;

interface OvernightStayDataPersisterInterface
{
    /**
     * @return OvernightStay
     */
    public function create(): OvernightStay;

    /**
     * @param OvernightStay $overnightStay
     * @return void
     */
    public function save(OvernightStay $overnightStay): void;

    /**
     * @param OvernightStay $overnightStay
     * @return void
     */
    public function remove(OvernightStay $overnightStay): void;
}
