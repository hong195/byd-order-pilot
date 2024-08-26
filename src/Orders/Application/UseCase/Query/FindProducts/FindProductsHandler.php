<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindProducts;

use App\Orders\Application\DTO\ProductDataTransformer;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindProductsHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository      $orderRepository      the order repository instance
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private OrderRepository $orderRepository,
        private AccessControlService $accessControlService,
        private ProductDataTransformer $productDataTransformer
    ) {
    }

    /**
     * Invokes the FindARollQuery and returns the FindOrderResult.
     *
     * @return FindProductsResult the result of finding the order
     */
    public function __invoke(FindProductsQuery $productsQuery): FindProductsResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $order = $this->orderRepository->findById($productsQuery->orderId);

        if (is_null($order)) {
            throw new NotFoundHttpException('Orders not found');
        }

        $products = $order->getProducts();

        $productsData = $this->productDataTransformer->fromProductsList($products->toArray());

        return new FindProductsResult($productsData);
    }
}
