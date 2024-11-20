<?php

declare(strict_types=1);

/**
 * Class PrintedProductPhotoUpdater.
 *
 * Description: This class is responsible for updating the photo of a PrintedProduct and saving changes
 * to the PrintedProductRepository.
 */

namespace App\ProductionProcess\Application\Service\PrintedProduct;

use App\ProductionProcess\Application\DTO\TakePhotoDTO;
use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\Shared\Application\Service\AssetUrlServiceInterface;
use App\Shared\Infrastructure\Repository\MediaFileRepository;

/**
 * Class RollMaker.
 *
 * Description: This class is responsible for creating rolls using the RollFactory, assigning a printer to the roll, and saving it to the RollRepository.
 */
final readonly class PrintedProductPhotoUpdater
{
    /**
     * Class constructor.
     *
     * @param PrintedProductRepositoryInterface $printedProductRepository the job repository instance
     * @param MediaFileRepository               $mediaFileRepository
     * @param AssetUrlServiceInterface          $assetUrlService
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private MediaFileRepository $mediaFileRepository, public AssetUrlServiceInterface $assetUrlService)
    {
    }

    /**
     * @param TakePhotoDTO $dto
     *
     * @return string
     */
    public function upload(TakePhotoDTO $dto): string
    {
        $printedProduct = $this->printedProductRepository->findById($dto->productId);

        $newPhoto = $dto->photoId ? $this->mediaFileRepository->findById($dto->photoId) : null;

        if ($printedProduct->getPhoto()) {
            $this->mediaFileRepository->remove($printedProduct->getPhoto());
        }

        $printedProduct->setPhoto($newPhoto);

        $this->printedProductRepository->save($printedProduct);

        return $this->assetUrlService->getLink($newPhoto->getPath());
    }
}
