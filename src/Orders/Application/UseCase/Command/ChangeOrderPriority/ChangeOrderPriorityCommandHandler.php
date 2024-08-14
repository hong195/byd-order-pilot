<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ChangeOrderPriority;

use App\Orders\Application\AccessControll\AccessControlService;
use App\Orders\Domain\Repository\OrderRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class ChangeOrderPriorityCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param OrderRepositoryInterface $orderRepository      the order repository
     * @param AccessControlService     $accessControlService the access control service
     */
    public function __construct(private OrderRepositoryInterface $orderRepository, private AccessControlService $accessControlService)
    {
    }

    /**
     * Invokes the command to change the order priority.
     *
     * @param ChangeOrderPriorityCommand $command the change order priority command instance
     *
     * @throws NotFoundHttpException if the roll is not found
     */
    public function __invoke(ChangeOrderPriorityCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not change priority.');
        $order = $this->orderRepository->findById($command->id);

        if (!$order) {
            throw new NotFoundHttpException('Order not found');
        }

        $order->updateHasPriority($command->priority);
        $this->orderRepository->save($order);
    }
}
