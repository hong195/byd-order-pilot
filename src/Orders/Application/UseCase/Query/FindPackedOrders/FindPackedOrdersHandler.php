<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindPackedOrders;

use App\Orders\Application\DTO\Order\OrderDataTransformer;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class FindPackedOrdersHandler.
 */
final readonly class FindPackedOrdersHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository      $orderRepository      the order repository instance
     */
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderDataTransformer $orderDataTransformer,
    ) {
    }

    /**
     * Invokes the FindOrdersWithExtrasQuery handler.
     *
     * @param FindPackedOrdersQuery $query the query to find ready for packing orders
     *
     * @return FindPackedOrdersResult the result of finding ready for packing orders
     */
    public function __invoke(FindPackedOrdersQuery $query): FindPackedOrdersResult
    {
        $result = $this->orderRepository->findPacked();

        $orderData = $this->orderDataTransformer->fromOrdersEntityList($result);

        return new FindPackedOrdersResult($orderData);
    }
}
