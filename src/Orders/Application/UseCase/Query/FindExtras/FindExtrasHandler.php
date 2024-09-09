<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Query\FindExtras;

use App\Orders\Application\DTO\ExtraDataTransformer;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Query\QueryHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Handles the FindARollQuery and returns the FindARollResult.
 */
final readonly class FindExtrasHandler implements QueryHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param OrderRepository      $orderRepository      the order repository instance
     * @param AccessControlService $accessControlService the access control service instance
     */
    public function __construct(private OrderRepository $orderRepository,
        private AccessControlService $accessControlService,
        private ExtraDataTransformer $productDataTransformer,
    ) {
    }

    /**
     * Invokes the FindARollQuery and returns the FindOrderResult.
     *
     * @return FindExtrasResult the result of finding the order
     */
    public function __invoke(FindExtrasQuery $productsQuery): FindExtrasResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'Access denied');

        $order = $this->orderRepository->findById($productsQuery->orderId);

        if (is_null($order)) {
            throw new NotFoundHttpException('Orders not found');
        }

        $extras = $order->getExtras();

        $extrasData = $this->productDataTransformer->fromExtrasList($extras->toArray());

        return new FindExtrasResult($extrasData);
    }
}
