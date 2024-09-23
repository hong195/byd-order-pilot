<?php

namespace App\ProductionProcess\Domain\Service\Roll\OrdersCheckInProcess;

/**
 * Interface ArrangeServiceInterface.
 */
interface OrdersCheckInInterface
{
    /**
     * Arranges the orders rolls.
     */
    public function checkIn(): void;
}
