<?php

namespace App\Orders\Domain\Service\OrdersCheckInProcess;

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
