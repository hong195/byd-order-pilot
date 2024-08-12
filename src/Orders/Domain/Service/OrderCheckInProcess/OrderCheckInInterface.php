<?php

namespace App\Orders\Domain\Service\OrderCheckInProcess;

/**
 * Interface ArrangeServiceInterface.
 */
interface OrderCheckInInterface
{
    /**
     * Arranges the orders rolls.
     */
    public function checkIn(): void;
}
