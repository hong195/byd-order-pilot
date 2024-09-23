<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ReprintOrder;

use App\ProductionProcess\Domain\Exceptions\OrderReprintException;
use App\ProductionProcess\Domain\Service\Order\ReprintOrder;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class ReprintCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private ReprintOrder $reprintOrder, private AccessControlService $accessControlService)
    {
    }

    /**
     * Handles a ReprintOrderCommand.
     *
     * @param ReprintOrderCommand $command The command to handle
     *
     * @throws NotFoundHttpException
     * @throws OrderReprintException
     */
    public function __invoke(ReprintOrderCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');
        $this->reprintOrder->handle($command->orderId);
    }
}
