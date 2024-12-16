<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CheckInPrintedProducts;

use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\AutoCheckInPrintedProductsService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class CheckInPrintedProductsCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     */
    public function __construct(private AutoCheckInPrintedProductsService $checkInService)
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
        return new CheckInPrintedProductsCommandResult($this->checkInService->arrange(printedProductIds: $command->printedProductIds));
    }
}
