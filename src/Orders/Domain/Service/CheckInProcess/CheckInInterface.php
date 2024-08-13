<?php

namespace App\Orders\Domain\Service\CheckInProcess;

/**
 * Interface ArrangeServiceInterface.
 */
interface CheckInInterface
{
    /**
     * Arranges the orders rolls.
     */
    public function checkIn(): void;
}
