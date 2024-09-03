<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\UnPackMainProduct;

use App\Orders\Domain\Exceptions\CantPackMainProductException;
use App\Orders\Domain\Service\Order\Pack\UnPackMainProduct;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UnPackMainProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private UnPackMainProduct $unPackMainProduct, private AccessControlService $accessControlService)
    {
    }

    /**
     * Handles a PackMainProductCommand.
     *
     * @param UnPackMainProductCommand $command The command to handle
     *
     * @throws NotFoundHttpException
     * @throws CantPackMainProductException
     */
    public function __invoke(UnPackMainProductCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');
        $this->unPackMainProduct->handle(rollId: $command->rollId, orderId: $command->orderId);
    }
}
