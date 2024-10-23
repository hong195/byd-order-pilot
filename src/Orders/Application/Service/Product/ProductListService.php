<?php

declare(strict_types=1);

namespace App\Orders\Application\Service\Product;

use App\Orders\Application\DTO\Product\ProductData;
use App\Orders\Domain\Aggregate\Product;
use App\Orders\Domain\DTO\ProcessDTO;
use App\Orders\Domain\Repository\ProductFilter;
use App\Orders\Domain\Repository\ProductRepositoryInterface;
use App\Orders\Infrastructure\Repository\OrderRepository;
use App\Shared\Application\Service\AssetUrlServiceInterface;

/**
 * Class ProductListService.
 *
 * @param OrderRepository                $orderRepository       Instance of order repository
 * @param ProductProcessServiceInterface $productProcessService Instance of product process service
 * @param AssetUrlServiceInterface       $assetUrlService       Instance of asset URL service
 */
final readonly class ProductListService
{
    /**
     * Class constructor.
     *
     * @param ProductProcessServiceInterface $productProcessService Instance of product process service
     */
    public function __construct(private ProductRepositoryInterface $productRepository, private ProductProcessServiceInterface $productProcessService, private AssetUrlServiceInterface $assetUrlService)
    {
    }

    /**
     * Get the list of products for a given order ID and product IDs.
     *
     * @param ?int   $orderId    The ID of the order
     * @param int[] $productIds Array of product IDs
     *
     * @return array Array of ProductData objects representing the products
     */
    public function getList(?int $orderId = null, array $productIds = []): array
    {
        $products = $this->productRepository->findByFilter(
            new ProductFilter(orderId: $orderId, productIds: $productIds)
        );

        if ($products->isEmpty()) {
            return [];
        }

        $result = [];
        $processes = $this->productProcessService->processByProductIds(array_map(fn (Product $product) => $product->getId(), $products->toArray()));

        foreach ($products as $product) {
            $process = $processes->filter(fn (ProcessDTO $process) => $process->productId === $product->getId())->first();

            $processData = $process ? new ProcessDTO(
                productId: $process->productId,
                process: $process->process,
                rollId: $process->rollId,
                isReprint: $process->isReprint,
                isReadyForPacking: $process->isReadyForPacking,
            ) : null;

            $result[] = new ProductData(
                id: $product->getId(),
                length: $product->getLength(),
                filmType: $product->filmType,
                orderNumber: $product->getOrderNumber(),
                cutFile: $product->getCutFile() ? $this->assetUrlService->getLink($product->getCutFile()->getPath()) : null,
                printFile: $product->getPrintFile() ? $this->assetUrlService->getLink($product->getPrintFile()->getPath()) : null,
                isPacked: $product->isPacked(),
                addedAt: $product->getDateAdded(),
                process: $processData,
            );
        }

        return $result;
    }
}
