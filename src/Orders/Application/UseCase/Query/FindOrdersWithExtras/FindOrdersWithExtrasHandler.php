<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindOrdersWithExtras;

use App\Orders\Application\DTO\Order\OrderDataTransformer;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\Query\QueryHandlerInterface;

/**
 * Class FindPackedOrdersHandler.
 */
final readonly class FindOrdersWithExtrasHandler implements QueryHandlerInterface
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
     * @param FindOrdersWithExtrasQuery $query the query to find ready for packing orders
     *
     * @return FindOrdersWithExtrasResult the result of finding ready for packing orders
     */
    public function __invoke(FindOrdersWithExtrasQuery $query): FindOrdersWithExtrasResult
    {
        $result = $this->orderRepository->findOnlyWithExtras();

        $orderData = $this->orderDataTransformer->fromOrdersEntityList($result);

        return new FindOrdersWithExtrasResult($orderData);
    }
}
