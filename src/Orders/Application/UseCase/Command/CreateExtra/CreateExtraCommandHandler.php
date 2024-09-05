<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\CreateExtra;

use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Orders\Domain\Service\Order\Extra\ExtraMaker;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class CreateExtraCommandHandler implements CommandHandlerInterface
{
    /**
     * Class CreateExtraCommandHandler.
     */
    public function __construct(private AccessControlService $accessControlService, private OrderRepositoryInterface $orderRepository, private ExtraMaker $extraMaker)
    {
    }

    /**
     * Class CreateExtraCommandHandler.
     */
    public function __invoke(CreateExtraCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $order = $this->orderRepository->findById($command->orderId);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        $extra = $this->extraMaker->make($command->name, $command->orderNumber, $command->count);

        $order->addExtra($extra);

        $this->orderRepository->save($order);
    }
}
