<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\Service\PrintedProduct;

use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductData;
use App\ProductionProcess\Application\DTO\PrintedProduct\PrintedProductDataTransformer;
use App\ProductionProcess\Domain\Aggregate\PrintedProduct;
use App\ProductionProcess\Domain\Repository\PrintedProductFilter;
use App\ProductionProcess\Domain\Repository\PrintedProductRepositoryInterface;
use App\ProductionProcess\Domain\Service\PrintedProduct\GroupService;
use App\Shared\Application\Service\AssetUrlServiceInterface;
use App\Shared\Domain\Entity\MediaFile;
use App\Shared\Infrastructure\Repository\MediaFileRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class PrintedProductListService.
 *
 * Provides methods for retrieving a list of printed products with file URLs.
 */
final readonly class PrintedProductListService
{
    /**
     * Constructor for initializing dependencies.
     *
     * @param MediaFileRepository               $mediaFileRepository    Repository for media files
     * @param GroupService                      $groupService           Service for handling groups
     * @param PrintedProductDataTransformer     $productDataTransformer Data transformer for printed products
     * @param PrintedProductRepositoryInterface $productRepository      Interface for interacting with printed products repository
     * @param AssetUrlServiceInterface          $assetUrlService        Service for generating asset URLs
     */
    public function __construct(
        private MediaFileRepository $mediaFileRepository, private GroupService $groupService,
        private PrintedProductDataTransformer $productDataTransformer, private PrintedProductRepositoryInterface $productRepository, private AssetUrlServiceInterface $assetUrlService,
        private RelatedProductsInterface $relatedProducts,
    ) {
    }

    /**
     * Retrieves a list of printed products based on the provided filter.
     *
     * @param PrintedProductFilter $filter The filter criteria for printed products
     *
     * @return array<int, PrintedProductData[]> An array of transformed printed products including cut and print file URLs
     */
    public function getList(PrintedProductFilter $filter): array
    {
        $printedProducts = $this->productRepository->findByFilter($filter);

        if (empty($printedProducts)) {
            return [];
        }

        $result = [];

        $mediaFiles = $this->mediaFileRepository->findByOwnerIds(array_map(fn (PrintedProduct $product) => $product->relatedProductId, $printedProducts));

        $groups = $this->groupService->handle(new ArrayCollection($printedProducts));
        $relatedProducts = new ArrayCollection($this->relatedProducts->findProductsByIds(array_map(fn (PrintedProduct $product) => $product->relatedProductId, $printedProducts)));

        foreach ($groups as $group => $items) {
            /** @var PrintedProduct $item */
            foreach ($items as $item) {
                /** @var MediaFile|null $cutFile */
                $cutFile = (new ArrayCollection($mediaFiles))->filter(fn (MediaFile $mediaFile) => $mediaFile->getOwnerId() === $item->relatedProductId && 'cut_file' === $mediaFile->getType())->first();
                /** @var MediaFile|null $printFile */
                $printFile = (new ArrayCollection($mediaFiles))->filter(fn (MediaFile $mediaFile) => $mediaFile->getOwnerId() === $item->relatedProductId && 'print_file' === $mediaFile->getType())->first();

                $isPacked = $relatedProducts->findFirst(fn (int $index, $relatedProduct) => $relatedProduct->id === $item->relatedProductId)->isPacked;

                $result[$group][] = $this->productDataTransformer->fromEntity(
                    product: $item,
                    cutFileUrl: $cutFile ? $this->assetUrlService->getLink($cutFile?->getPath()) : null,
                    printFileUrl: $printFile ? $this->assetUrlService->getLink($printFile?->getPath()) : null,
                    isPacked: $isPacked,
                );
            }
        }

        return $result;
    }
}
