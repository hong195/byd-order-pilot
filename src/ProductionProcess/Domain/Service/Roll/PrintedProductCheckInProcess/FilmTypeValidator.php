<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use Doctrine\Common\Collections\Collection;

/**
 * Validates printed products if they printable by one printer and have the same film type.
 */
final readonly class FilmTypeValidator
{
    /**
     * Validates that all printed products have the same film type.
     *
     * @param Collection<PrintedProduct> $printedProducts Array of printed products to validate
     *
     * @throws ManualArrangeException If the printed products have different film types
     */
    public function validate(Collection $printedProducts): void
    {
        // all products must have the same film type
        $printedProducts->map(function (PrintedProduct $printedProduct) use ($printedProducts) {
            if ($printedProduct->getFilmType() !== $printedProducts->first()->getFilmType()) {
                ManualArrangeException::because('Given printed products have different film types');
            }
        });
    }
}
