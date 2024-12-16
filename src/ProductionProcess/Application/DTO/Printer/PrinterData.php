<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\DTO\Printer;

use App\ProductionProcess\Domain\Aggregate\Printer\Printer;

/**
 * Class RollData.
 *
 * Represents a printer data.
 */
final readonly class PrinterData
{
    /**
     * Constructor for creating an instance of the class.
     *
     * @param int    $id   the ID of the object
     * @param string $name the name of the object
     */
    public function __construct(public string $id, public string $name)
    {
    }
}
