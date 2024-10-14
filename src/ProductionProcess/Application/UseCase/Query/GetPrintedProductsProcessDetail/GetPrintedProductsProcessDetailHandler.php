<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\GetPrintedProductsProcessDetail;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductProcessDataTransformer;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

final readonly class GetPrintedProductsProcessDetailHandler implements QueryHandlerInterface
{
    /**
     * Constructor for the class.
     *
     * @param AccessControlService $accessControlService the access control service dependency
     */
    public function __construct(private AccessControlService $accessControlService, private PrintedProductRepositoryInterface $printedProductRepository, private PrintedProductProcessDataTransformer $transformer)
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
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $result = $this->printedProductRepository->findByRelatedProductIds($query->relatedProductIds);

        $resultData = $this->transformer->fromPrintedProductList($result);

        return new GetPrintedProductsProcessDetailResult($resultData);
    }
}
