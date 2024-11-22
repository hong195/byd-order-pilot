<?php

declare(strict_types=1);

namespace App\ProductionProcess\Domain\Service\Printer;

use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Aggregate\Printer\Printer;
use App\ProductionProcess\Domain\Exceptions\PrinterMatchException;
use App\ProductionProcess\Domain\Repository\Printer\ConditionRepositoryInterface;
use App\Shared\Domain\Exception\DomainException;

final readonly class ProductPrinterMatcher
{
    /**
     * Constructor for the class.
     */
    public function __construct(private ConditionRepositoryInterface $conditionRepository)
    {
    }

    /**
     * @throws PrinterMatchException
     * @throws DomainException
     */
    public function match(PrintedProduct $printedProduct): Printer
    {
        $conditions = $this->conditionRepository->all();

        foreach ($conditions as $condition) {
            $isSatisfiedBy = $condition->isSatisfiedBy(
                filmType: $printedProduct->getFilmType(),
                laminationType: $printedProduct->getLaminationType(),
            );

            if ($isSatisfiedBy) {
                return $condition->printer;
            }
        }

        /* @phpstan-ignore-next-line */
        PrinterMatchException::because('No printer found for the given product');
    }
}
