<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Query\FindPrintedProducts;

use App\ProductionProcess\Application\Service\PrintedProduct\PrintedProductListService;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductFilter;
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
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private AccessControlService $accessControlService, private PrintedProductListService $printedProductListService)
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

        $listData = $this->printedProductListService->getList($filter);

        return new FindPrintedProductsQueryResult($listData);
    }
}
