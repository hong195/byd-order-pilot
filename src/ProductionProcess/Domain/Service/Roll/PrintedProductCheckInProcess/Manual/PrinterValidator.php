<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Service\Printer\ProductPrinterMatcher;
use App\Shared\Domain\Exception\DomainException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Validates printed products if they printable by one printer and have the same film type.
 */
final readonly class PrinterValidator
{
    public function __construct(private ProductPrinterMatcher $productPrinterMatcher)
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
        $printers = new ArrayCollection([]);

        foreach ($printedProducts as $printedProduct) {
            if (!$printers->contains($this->productPrinterMatcher->match($printedProduct))) {
                $printers->add($this->productPrinterMatcher->match($printedProduct));
            }
        }

        if (count($printers) > 1) {
            ManualArrangeException::because('Given printed products have different printers');
        }
    }
}
