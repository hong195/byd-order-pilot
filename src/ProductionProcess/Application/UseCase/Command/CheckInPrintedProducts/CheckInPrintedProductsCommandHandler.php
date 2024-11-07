<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CheckInPrintedProducts;

use App\ProductionProcess\Domain\Exceptions\UnassignedPrintedProductsException;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\PrintedProductsCheckInService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class CheckInPrintedProductsCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private AccessControlService $accessControlService, private PrintedProductsCheckInService $checkInService)
    {
    }

    /**
     * Invokes the AssignEmployeeToRollCommand.
     *
     * @param CheckInPrintedProductsCommand $command the assign employee to roll command
     *
     * @throws UnassignedPrintedProductsException
     */
    public function __invoke(CheckInPrintedProductsCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        $this->checkInService->arrange(printedProductIds: $command->printedProductIds);
    }
}
