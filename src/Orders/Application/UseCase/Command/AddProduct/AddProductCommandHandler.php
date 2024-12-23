<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase\Command\AddProduct;

use App\Orders\Application\DTO\Product\ProductCreateDTO;
use App\Orders\Application\Service\Product\ProductService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class ManuallyAddOrderCommandHandler.
 */
final readonly class AddProductCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService $accessControlService the access control service
     */
    public function __construct(private ProductService $productService)
    {
    }

    /**
     * Handles the CreatePrintedProductCommand.
     *
     * @param AddProductCommand $command The command containing the product information
     *
     * @return string The ID of the created product
     *
     * @throws \InvalidArgumentException If access control is not granted
     */
    public function __invoke(AddProductCommand $command): string
    {
        $dto = new ProductCreateDTO(
            orderId: $command->orderId,
            length: $command->length,
            filmType: $command->filmType,
            laminationType: $command->laminationType,
            cutFileId: $command->cutFileId,
            printFileId: $command->printFileId,
        );
        $product = $this->productService->create($dto);

        return $product->getId();
    }
}
