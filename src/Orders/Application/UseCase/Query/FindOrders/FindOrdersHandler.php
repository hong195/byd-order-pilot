<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindOrders;

use App\Orders\Application\DTO\OrderDataTransformer;
use App\Orders\Domain\Repository\OrderFilter;
use App\Orders\Domain\Service\Order\GroupService;
use App\Orders\Domain\ValueObject\Status;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class FindOrdersHandler.
 */
final readonly class FindOrdersHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository      $orderRepository      the order repository instance
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private OrderRepository $orderRepository,
        private AccessControlService $accessControlService,
        private OrderDataTransformer $orderDataTransformer,
        private GroupService $groupService,
    ) {
    }

    /**
     * Invokes the FindARollQuery and returns the FindOrderResult.
     *
     * @param FindOrdersQuery $orderQuery the query used to find the order
     *
     * @return FindOrdersResult the result of finding the orders
     */
    public function __invoke(FindOrdersQuery $orderQuery): FindOrdersResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $filter = new OrderFilter(rollId: $orderQuery->rollId, status: $orderQuery->status ? Status::from($orderQuery->status) : null);

        $result = $this->orderRepository->findByFilter($filter);

        $ordersByGroupedLamination = $this->groupService->handle(new ArrayCollection($result->items));

        $orderData = $this->orderDataTransformer->fromLaminationGroup($ordersByGroupedLamination);

        return new FindOrdersResult($orderData);
    }
}
