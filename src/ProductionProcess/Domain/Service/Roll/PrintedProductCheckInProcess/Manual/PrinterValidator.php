<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\GroupPrinterService;
use Doctrine\Common\Collections\Collection;

/**
 * Validates printed products if they printable by one printer and have the same film type.
 */
final readonly class PrinterValidator
{
    public function __construct(private GroupPrinterService $groupPrinterService)
    {
    }

    /**
     * Validates the printed products by checking if they have the same printer.
     *
     * @param Collection $printedProducts The collection of printed products to validate
     */
    public function validate(Collection $printedProducts): void
    {
        $printers = $this->groupPrinterService->group($printedProducts)->map(fn ($group) => $group->printer);

        if ($printers->count() > 1) {
            ManualArrangeException::because('Given printed products have different printers');
        }
    }
}
