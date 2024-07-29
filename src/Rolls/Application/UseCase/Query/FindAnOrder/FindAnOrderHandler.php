<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindAnOrder;

use App\Rolls\Application\AccessControll\AccessControlService;
use App\Rolls\Application\DTO\OrderDataTransformer;
use App\Rolls\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindAnOrderHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository      $orderRepository      the order repository instance
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private OrderRepository $orderRepository,
        private AccessControlService $accessControlService,
        private OrderDataTransformer $orderDataTransformer
    ) {
    }

    /**
     * Invokes the FindARollQuery and returns the FindOrderResult.
     *
     * @param FindAnOrderQuery $orderQuery the query used to find the order
     *
     * @return FindAnOrderResult the result of finding the order
     */
    public function __invoke(FindAnOrderQuery $orderQuery): FindAnOrderResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $order = $this->orderRepository->findById($orderQuery->orderId);

        if (is_null($order)) {
            throw new NotFoundHttpException('Order not found');
        }

        $orderData = $this->orderDataTransformer->fromEntity($order);

        return new FindAnOrderResult($orderData);
    }
}
