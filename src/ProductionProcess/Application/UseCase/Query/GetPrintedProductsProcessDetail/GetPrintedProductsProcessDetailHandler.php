<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\GetPrintedProductsProcessDetail;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductProcessDataTransformer;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetPrintedProductsProcessDetailHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private PrintedProductProcessDataTransformer $transformer)
    {
    }

    /**
     * Invokes the object as a function.
     *
     * @param GetPrintedProductsProcessDetailQuery $query the query object for getting printed product process detail
     *
     * @return GetPrintedProductsProcessDetailResult the result of getting printed product process detail
     *
     * @throws \Exception if access is denied
     */
    public function __invoke(GetPrintedProductsProcessDetailQuery $query): GetPrintedProductsProcessDetailResult
    {
        $result = $this->printedProductRepository->findByRelatedProductIds($query->relatedProductIds);

        $resultData = $this->transformer->fromPrintedProductList($result);

        return new GetPrintedProductsProcessDetailResult($resultData);
    }
}
