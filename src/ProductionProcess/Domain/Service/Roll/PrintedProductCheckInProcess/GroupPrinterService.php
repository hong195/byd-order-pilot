<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Printer\Printer;
use App\ProductionProcess\Domain\Exceptions\PrinterMatchException;
use App\ProductionProcess\Domain\Repository\Printer\PrinterRepositoryInterface;
use App\ProductionProcess\Domain\Service\Printer\ProductPrinterMatcher;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\Groups\PrinterGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class GroupPrinterService
{
    /**
     * @var Collection<PrinterGroup>
     */
    private Collection $groups;

    /**
     * Constructor for initializing PrinterGroupManager.
     *
     * @param PrinterRepositoryInterface $printerRepository The printer repository to fetch printers from
     */
    public function __construct(private readonly PrinterRepositoryInterface $printerRepository, private ProductPrinterMatcher $printerMatcher)
    {
        $this->groups = new ArrayCollection([]);
    }

    /**
     * Groups printed products based on printers' capabilities and default group assignment.
     *
     * @param iterable<PrintedProduct> $printedProducts The products to be grouped
     *
     * @return Collection<PrinterGroup> The grouped products
     */
    public function group(iterable $printedProducts): Collection
    {
        $printers = $this->printerRepository->all();

        $this->groups = $printers->map(fn (Printer $printer) => new PrinterGroup($printer, []));
        $defaultGroup = $this->groups->filter(fn (PrinterGroup $printerGroup) => $printerGroup->printer->isDefault)->first();

        foreach ($printedProducts as $printedProduct) {
            /* @var Printer|bool $printer */

            try {
                $printer = $this->printerMatcher->match($printedProduct);
                $currentGroup = $this->groups->filter(fn (PrinterGroup $printerGroup) => $printerGroup->printer->getId() === $printer->getId())->first();
                $currentGroup->addProductTo($printedProduct);
            } catch (PrinterMatchException $e) {
                $defaultGroup->addProductTo($printedProduct);
            }
        }

        return $this->groups;
    }
}
