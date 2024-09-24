<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindProducts;

use App\Orders\Application\DTO\Order\OrderDataTransformer;
use App\Orders\Application\DTO\Product\ProductDataTransformer;
use App\Orders\Domain\Repository\OrderFilter;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class FindOrdersHandler.
 */
final readonly class FindProductsHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository      $orderRepository      the order repository instance
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(
		private ProductRepositoryInterface $productRepository,
        private AccessControlService $accessControlService,
        private ProductDataTransformer $productDataTransformer,
    ) {
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
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $result = $this->productRepository->findByOrderId($query->orderId);

        $productsData = $this->productDataTransformer->fromProductsEntityList($result);

        return new FindProductsResult($productsData);
    }
}
