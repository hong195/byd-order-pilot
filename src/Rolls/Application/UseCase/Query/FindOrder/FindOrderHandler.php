<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase\Query\FindOrder;

use App\Rolls\Application\AccessControll\AccessControlService;
use App\Rolls\Application\DTO\OrderData;
use App\Rolls\Application\Service\AssetUrlServiceInterface;
use App\Rolls\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindOrderHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository      $orderRepository      the order repository instance
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private OrderRepository $orderRepository, private AccessControlService $accessControlService, private AssetUrlServiceInterface $assetUrlService)
    {
    }

    /**
     * Invokes the FindARollQuery and returns the FindOrderResult.
     *
     * @param FindOrderQuery $orderQuery the query used to find the order
     *
     * @return FindOrderResult the result of finding the order
     */
    public function __invoke(FindOrderQuery $orderQuery): FindOrderResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $order = $this->orderRepository->findById($orderQuery->orderId);

        if (is_null($order)) {
            throw new NotFoundHttpException('Order not found');
        }

        $orderData = new OrderData(
            id: $order->getId(),
            status: $order->getStatus()->value,
            priority: $order->getPriority()->value,
            productType: $order->getProductType()->value,
            rollType: $order->getRollType()->value,
            addedAt: $order->getDateAdded(),
            laminationType: $order->getLaminationType()?->value,
            orderNumber: $order->getOrderNumber(),
            cutFile: $order->getCutFile() ? $this->assetUrlService->getLink($order->getCutFile()?->getPath()): null,
            printFile: $order->getPrintFile() ? $this->assetUrlService->getLink($order->getPrintFile()?->getPath()): null,
        );

        return new FindOrderResult($orderData);
    }
}
