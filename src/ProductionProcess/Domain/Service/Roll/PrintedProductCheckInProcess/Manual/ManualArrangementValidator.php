<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Manual;

use App\ProductionProcess\Domain\Exceptions\ManualArrangeException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\FilmTypeValidator;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\PrinterValidator;
use App\Shared\Domain\Exception\DomainException;
use Doctrine\Common\Collections\Collection;
use Proxies\__CG__\App\ProductionProcess\Domain\Aggregate\PrintedProduct;

final readonly class ManualArrangementValidator
{
    public function __construct(private PrinterValidator $printerValidator, private FilmTypeValidator $filmValidator)
    {
    }

    /**
     * Validates the printed products.
     *
     * @param Collection<PrintedProduct> $printedProducts The printed products to validate
     *
     * @throws ManualArrangeException
     * @throws DomainException
     */
    public function validate(Collection $printedProducts): void
    {
        $this->filmValidator->validate($printedProducts);
        $this->printerValidator->validate($printedProducts);
    }
}
