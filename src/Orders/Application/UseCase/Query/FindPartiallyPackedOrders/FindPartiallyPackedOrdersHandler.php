<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindPartiallyPackedOrders;

use App\Orders\Application\DTO\Order\OrderDataTransformer;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Class FindPackedOrdersHandler.
 */
final readonly class FindPartiallyPackedOrdersHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository $orderRepository the order repository instance
     */
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderDataTransformer $orderDataTransformer,
    ) {
    }

    /**
     * Invokes the FindOrdersWithExtrasQuery handler.
     *
     * @param FindPartiallyPackedOrdersQuery $query the query to find ready for packing orders
     *
     * @return FindPartiallyPackedOrdersResult the result of finding ready for packing orders
     */
    public function __invoke(FindPartiallyPackedOrdersQuery $query): FindPartiallyPackedOrdersResult
    {
        $result = $this->orderRepository->findPartiallyPacked();

        $orderData = $this->orderDataTransformer->fromOrdersEntityList($result);

        return new FindPartiallyPackedOrdersResult($orderData);
    }
}
