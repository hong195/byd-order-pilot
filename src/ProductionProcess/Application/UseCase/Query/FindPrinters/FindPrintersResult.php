<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrinters;

use App\ProductionProcess\Application\DTO\Printer\PrinterData;

/**
 * Class FindPrintersResult.
 *
 * Represents the result of finding printers in the application.
 */
final readonly class FindPrintersResult
{
    /**
     * Initializes a new instance of the MyDatabaseConnection class.
     *
     * @param PrinterData[] $items the printers data
     */
    public function __construct(public array $items)
    {
    }
}
