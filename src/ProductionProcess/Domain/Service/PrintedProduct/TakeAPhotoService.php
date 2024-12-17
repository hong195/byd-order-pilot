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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     */
    public function __construct(private PrintedProductRepositoryInterface $printedProductRepository, private MediaFileRepositoryInterface $mediaFileRepository, private RemoveMediaFileService $removeMediaFileService)
    {
    }

    /**
     * Uploads a new photo for a printed product.
     *
     * Retrieves the printed product by ID, throws NotFoundHttpException if not found.
     * Gets the old photo of the printed product and removes it if it exists.
     * Retrieves the new photo by ID and sets it as the photo of the printed product.
     * Saves the printed product with the new photo.
     *
     * @param string $productId the ID of the printed product
     * @param string $photoId   the ID of the new photo to upload
     *
     * @throws NotFoundHttpException if the printed product is not found
     */
    public function upload(string $productId, string $photoId): void
    {
        $printedProduct = $this->printedProductRepository->findById($productId);

        if (!$printedProduct) {
            throw new NotFoundHttpException('Printed product not found');
        }

        $oldPhoto = $printedProduct->getPhoto();

        if ($oldPhoto) {
            $this->removeMediaFileService->removePhoto(mediaFileId: $oldPhoto->getId());
        }

        $newPhoto = $this->mediaFileRepository->findById($photoId);

        $printedProduct->setPhoto($newPhoto);

        $this->printedProductRepository->save($printedProduct);
    }
}
