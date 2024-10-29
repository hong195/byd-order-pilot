<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\AssignPrintedProduct;

use App\ProductionProcess\Domain\Service\Roll\PrintedProductCheckInProcess\PrintedProductsCheckInService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class AssignPrintedProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Class MyClass.
     */
    public function __construct(private AccessControlService $accessControlService, private PrintedProductsCheckInService $checkInService)
    {
    }

    /**
     * Invokes the command to change the order priority.
     *
     * @param AssignPrintedProductCommand $command the change order priority command instance
     *
     * @throws NotFoundHttpException if the roll is not found
     * @throws \Exception
     */
    public function __invoke(AssignPrintedProductCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not change priority.');

        $this->checkInService->arrange([$command->printedProductId]);
    }
}
