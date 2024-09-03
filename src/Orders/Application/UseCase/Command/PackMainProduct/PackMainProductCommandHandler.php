<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\PackMainProduct;

use App\Orders\Domain\Exceptions\CantPackMainProductException;
use App\Orders\Domain\Service\Order\Pack\PackMainProduct;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class PackMainProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private PackMainProduct $packMainProduct, private AccessControlService $accessControlService)
    {
    }

    /**
     * Handles a PackMainProductCommand.
     *
     * @param PackMainProductCommand $command The command to handle
     *
     * @throws NotFoundHttpException
     * @throws CantPackMainProductException
     */
    public function __invoke(PackMainProductCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');
        $this->packMainProduct->handle(rollId: $command->rollId, orderId: $command->orderId);
    }
}
