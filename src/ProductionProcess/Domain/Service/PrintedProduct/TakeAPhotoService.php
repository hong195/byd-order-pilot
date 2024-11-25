<?php

declare(strict_types=1);

/**
 * Class TakeAPhotoService.
 *
 * Description: This class is responsible for updating the photo of a PrintedProduct and saving changes
 * to the PrintedProductRepository.
 */

namespace App\ProductionProcess\Domain\Service\PrintedProduct;

use App\ProductionProcess\Domain\Repository\PrintedProduct\PrintedProductRepositoryInterface;
use App\Shared\Domain\Repository\MediaFileRepositoryInterface;
use App\Shared\Domain\Service\RemoveMediaFileService;

/**
 * Class RollMaker.
 *
 * Description: This class is responsible for creating rolls using the RollFactory, assigning a printer to the roll, and saving it to the RollRepository.
 */
final readonly class TakeAPhotoService
{
    /**
     * Class constructor.
     *
     * @param PrintedProductRepositoryInterface $printedProductRepository the job repository instance
     * @param MediaFileRepositoryInterface      $mediaFileRepository
     * @param RemoveMediaFileService            $removeMediaFileService
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private MediaFileRepositoryInterface $mediaFileRepository, private RemoveMediaFileService $removeMediaFileService)
    {
    }

    /**
     * @param int $productId
     * @param int $photoId
     *
     * @return void
     */
    public function upload(int $productId, int $photoId): void
    {
        $printedProduct = $this->printedProductRepository->findById($productId);

        $oldPhoto = $printedProduct->getPhoto();

        if ($oldPhoto) {
            $this->removeMediaFileService->removePhoto(mediaFileId: $oldPhoto->getId());
        }

        $newPhoto = $this->mediaFileRepository->findById($photoId);

        $printedProduct->setPhoto($newPhoto);

        $this->printedProductRepository->save($printedProduct);
    }
}
