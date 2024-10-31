<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrintedProduct;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductDataTransformer;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handler class for finding printed products.
 */
final readonly class FindPrintedProductQueryHandler implements QueryHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private AccessControlService $accessControlService, private PrintedProductRepositoryInterface $printedProductRepository, private PrintedProductDataTransformer $transformer)
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
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $printedProduct = $this->printedProductRepository->findById($query->printedProductId);

        $printedProductData = $this->transformer->fromEntity($printedProduct);

        return new FindPrintedProductQueryResult($printedProductData);
    }
}
