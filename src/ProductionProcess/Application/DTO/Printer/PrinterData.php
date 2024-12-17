<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\Printer;

/**
 * Class RollData.
 *
 * Represents a printer data.
 */
final readonly class PrinterData
{
    /**
     * Class constructor.
     *
     * @param string $id          the ID of the object
     * @param string $name        the name of the object
     * @param bool   $isAvailable the availability status of the object
     */
    public function __construct(public string $id, public string $name, public bool $isAvailable)
    {
    }
}
