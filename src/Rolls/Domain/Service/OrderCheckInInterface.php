<?php

namespace App\Rolls\Domain\Service;

/**
 * Interface ArrangeServiceInterface.
 */
interface OrderCheckInInterface
{
    /**
     * Arranges the order into order stack.
     */

    public function checkIn(int $orderId): void;
}
