<?php

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

/**
 * Interface ArrangeServiceInterface.
 */
interface PrintedProductCheckInInterface
{
    /**
     * Arranges the orders rolls.
     */
    public function checkIn(): void;
}
