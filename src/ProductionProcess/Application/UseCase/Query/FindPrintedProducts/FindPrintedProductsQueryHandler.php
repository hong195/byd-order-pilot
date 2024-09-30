<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrintedProducts;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductDataTransformer;
use App\ProductionProcess\Domain\Repository\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Handler class for finding printed products.
 */
final readonly class FindPrintedProductsQueryHandler implements QueryHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService              $accessControlService     the access control service instance
     * @param PrintedProductRepositoryInterface $printedProductRepository the printed product repository instance
     * @param PrintedProductDataTransformer     $productDataTransformer   the product data transformer instance
     */
    public function __construct(private AccessControlService $accessControlService, private PrintedProductRepositoryInterface $printedProductRepository, private PrintedProductDataTransformer $productDataTransformer)
    {
    }

    /**
     * Invokes the class.
     *
     * @param FindPrintedProductsQuery $query the find printed products query
     *
     * @return FindPrintedProductsQueryResult the result of the find printed products query
     *
     * @throws \InvalidArgumentException if access is denied
     */
    public function __invoke(FindPrintedProductsQuery $query): FindPrintedProductsQueryResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $filter = new PrintedProductFilter($query->unassigned, $query->rollId);

        $list = $this->printedProductRepository->findByFilter($filter);

        $listData = $this->productDataTransformer->fromPrintedProductList($list);

        return new FindPrintedProductsQueryResult($listData);
    }
}
