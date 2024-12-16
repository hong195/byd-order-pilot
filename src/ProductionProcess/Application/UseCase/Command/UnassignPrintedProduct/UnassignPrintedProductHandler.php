<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\UnassignPrintedProduct;

use App\ProductionProcess\Domain\Service\PrintedProduct\UnAssignPrintedProduct;
use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\Auto\AutoPrintedProductsCheckInService;
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
    public function __construct(private UnAssignPrintedProduct $unAssignPrintedProduct)
    {
    }

	/**
	 * @param UnassignPrintedProductCommand $command
	 * @throws \Exception
	 */
    public function __invoke(UnassignPrintedProductCommand $command): void
    {
        $this->unAssignPrintedProduct->handle($command->id);
    }
}
