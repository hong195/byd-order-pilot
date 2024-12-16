<?php

declare(strict_types=1);

/**
 * Class TakePhotoCommandHandler.
 */

namespace App\ProductionProcess\Application\UseCase\Command\TakePhoto;

use App\ProductionProcess\Domain\Service\PrintedProduct\TakeAPhotoService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Service\AssetUrlServiceInterface;
use App\Shared\Domain\Repository\MediaFileRepositoryInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class ManuallyAddOrderCommandHandler.
 */
final readonly class TakePhotoCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param TakeAPhotoService            $photoUpdater
     * @param MediaFileRepositoryInterface $mediaFileRepository
     * @param AssetUrlServiceInterface     $assetUrlService
     */
    public function __construct(private TakeAPhotoService $photoUpdater, private MediaFileRepositoryInterface $mediaFileRepository, private AssetUrlServiceInterface $assetUrlService)
    {
    }

    /**
     * Handles the CreatePrintedProductCommand.
     *
     * @param TakePhotoCommand $command The command information for uploading photo
     *
     * @return string The url of stored product photo
     *
     * @throws \InvalidArgumentException If access control is not granted
     */
    public function __invoke(TakePhotoCommand $command): string
    {
        $this->photoUpdater->upload(productId: $command->productId, photoId: $command->photoId);

        $photoMediaFile = $this->mediaFileRepository->findById($command->photoId);

        return $this->assetUrlService->getLink($photoMediaFile->getPath());
    }
}
