<?php

namespace App\ProductionProcess\Domain\Service\Roll\JobsCheckInProcess;

/**
 * Interface ArrangeServiceInterface.
 */
interface JobCheckInInterface
{
    /**
     * Arranges the orders rolls.
     */
    public function checkIn(): void;
}
