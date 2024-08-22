<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindPrinters;

use App\Orders\Application\DTO\PrinterData;

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
