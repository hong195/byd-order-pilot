<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\UnassignPrintedProduct;

use App\ProductionProcess\Domain\Service\PrintedProduct\UnAssignPrintedProduct;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\AutoArrangePrintedProductsService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class UnassignPrintedProductHandler implements CommandHandlerInterface
{
    /**
     * Class MyClass.
     */
    public function __construct(private UnAssignPrintedProduct $unAssignPrintedProduct, private AccessControlService $accessControlService)
    {
    }

	/**
	 * @param UnassignPrintedProductCommand $command
	 * @throws \Exception
	 */
    public function __invoke(UnassignPrintedProductCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not change priority.');

        $this->unAssignPrintedProduct->handle($command->id);
    }
}
