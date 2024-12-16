<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\CreatePrintedProduct;

use App\ProductionProcess\Application\DTO\PrintedProduct\CreatedPrintedProductData;
use App\ProductionProcess\Application\Service\PrintedProduct\PrintedProductMaker;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * Class ManuallyAddOrderCommandHandler.
 */
final readonly class CreatePrintedProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     */
    public function __construct(private PrintedProductMaker $jobMaker)
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
    public function __invoke(CreatePrintedProductCommand $command): string
    {
        $printedProduct = $this->jobMaker->make(CreatedPrintedProductData::fromCommand($command));

        return $printedProduct->getId();
    }
}
