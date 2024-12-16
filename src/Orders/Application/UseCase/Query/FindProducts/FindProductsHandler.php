<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindProducts;

use App\Orders\Application\Service\Product\ProductListService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class FindPackedOrdersHandler.
 */
final readonly class FindProductsHandler implements QueryHandlerInterface
{
    /**
     * Class constructor.
     */
    public function __construct(private ProductListService $productListService) {
    }

    /**
     * Invokes the class.
     *
     * @param FindProductsQuery $query the find products query instance
     *
     * @return FindProductsResult the find products result instance
     */
    public function __invoke(FindProductsQuery $query): FindProductsResult
    {
        $result = $this->productListService->getList(orderId: $query->orderId, productIds: $query->productIds);

        return new FindProductsResult($result);
    }
}
