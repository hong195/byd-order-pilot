<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\ReprintPrintedProduct;

use App\ProductionProcess\Domain\Service\PrintedProduct\ReprintPrintedProduct;
use App\ProductionProcess\Domain\ValueObject\Process;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class UpdateRollCommandHandler handles updating a Roll entity.
 */
readonly class ReprintPrintedProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Class constructor.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private ReprintPrintedProduct $reprintPrintedProduct)
    {
    }

    /**
     * Handles a ReprintOrderCommand.
     *
     * @param ReprintPrintedProductCommand $command The command to handle
     *
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function __invoke(ReprintPrintedProductCommand $command): void
    {
        $this->reprintPrintedProduct->handle(
            printedProductId: $command->printedProductId,
            process: Process::from($command->process),
            reason: $command->reason
        );
    }
}
