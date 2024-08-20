<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\ShipAndCollectOrders;

use App\Orders\Domain\Exceptions\ShipAndCollectionException;
use App\Orders\Domain\Service\ShipAndCollectOrdersService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class ShipAndCollectOrdersCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param ShipAndCollectOrdersService $shipAndCollectOrdersService the ship and collect orders service
     * @param AccessControlService        $accessControlService        the access control service
     */
    public function __construct(private ShipAndCollectOrdersService $shipAndCollectOrdersService, private AccessControlService $accessControlService)
    {
    }

    /**
     * Handle the ShipAndCollectOrdersCommand.
     *
     * @param ShipAndCollectOrdersCommand $command the command to be handled
     *
     * @throws ShipAndCollectionException
     */
    public function __invoke(ShipAndCollectOrdersCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->shipAndCollectOrdersService->handle($command->id);
    }
}
