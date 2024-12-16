<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrintedProduct;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductDataTransformer;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Handler class for finding printed products.
 */
final readonly class FindPrintedProductQueryHandler implements QueryHandlerInterface
{
    /**
     * Class constructor.
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private PrintedProductDataTransformer $transformer)
    {
    }

    /**
     * Invokes the class.
     *
     * @param FindPrintedProductQuery $query the find printed products query
     *
     * @return FindPrintedProductQueryResult the result of the find printed products query
     *
     * @throws \InvalidArgumentException if access is denied
     */
    public function __invoke(FindPrintedProductQuery $query): FindPrintedProductQueryResult
    {
        $printedProduct = $this->printedProductRepository->findById($query->printedProductId);

        $printedProductData = $this->transformer->fromEntity($printedProduct);

        return new FindPrintedProductQueryResult($printedProductData);
    }
}
