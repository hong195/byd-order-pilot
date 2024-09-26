<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CreatePrintedProduct;

use App\ProductionProcess\Application\DTO\PrintedProduct\CreatedPrintedProductData;
use App\ProductionProcess\Application\Service\PrintedProduct\PrintedProductMaker;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class ManuallyAddOrderCommandHandler.
 */
final readonly class CreatePrintedProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private AccessControlService $accessControlService, private PrintedProductMaker $jobMaker)
    {
    }

    /**
     * Handles the CreatePrintedProductCommand.
     *
     * @param CreatePrintedProductCommand $command The command containing the product information
     *
     * @return int The ID of the created product
     *
     * @throws \InvalidArgumentException If access control is not granted
     */
    public function __invoke(CreatePrintedProductCommand $command): int
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');

        $printedProduct = $this->jobMaker->make(CreatedPrintedProductData::fromCommand($command));

        return $printedProduct->getId();
    }
}
