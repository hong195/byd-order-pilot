<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\GroupPrinterService;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Groups\PrinterGroup;
use App\Shared\Domain\Exception\DomainException;
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
     *
     * @throws DomainException
     */
    public function validate(Collection $printedProducts): void
    {
        $printersGroup = $this->groupPrinterService->group($printedProducts)->filter(fn (PrinterGroup $group) => count($group->getProducts()) > 1);

        if ($printersGroup->count() > 1) {
            ManualArrangeException::because('Given printed products have different printers');
        }
    }
}
