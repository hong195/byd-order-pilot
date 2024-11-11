<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CheckInPrintedProducts;

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
     * Class constructor.
     *
     * @param CheckInPrintedProductsCommand $command the command to check in printed products
     *
     * @return CheckInPrintedProductsCommandResult the result of checking in printed products
     */
    public function __invoke(CheckInPrintedProductsCommand $command): CheckInPrintedProductsCommandResult
    {
        AssertService::true($this->accessControlService->isGranted(), 'No access to handle the command');

        return new CheckInPrintedProductsCommandResult($this->checkInService->arrange(printedProductIds: $command->printedProductIds));
    }
}
