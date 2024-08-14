<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnassignOrder;

use App\Orders\Application\AccessControll\AccessControlService;
use App\Orders\Domain\Exceptions\OrderCantBeUnassignedException;
use App\Orders\Domain\Service\Order\UnassignOrder;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UnassignOrderCommandHandler implements CommandHandlerInterface
{
    /**
     * Class MyClass.
     */
    public function __construct(private UnassignOrder $unassignOrder, private AccessControlService $accessControlService)
    {
    }

    /**
     * Invokes the command to change the order priority.
     *
     * @param UnassignOrderCommand $command the change order priority command instance
     *
     * @throws NotFoundHttpException          if the roll is not found
     * @throws OrderCantBeUnassignedException
     */
    public function __invoke(UnassignOrderCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not change priority.');

        $this->unassignOrder->handle($command->id);
    }
}
