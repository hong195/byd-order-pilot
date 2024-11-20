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
     * @param AccessControlService         $accessControlService the access control service
     * @param TakeAPhotoService            $photoUpdater
     * @param MediaFileRepositoryInterface $mediaFileRepository
     * @param AssetUrlServiceInterface     $assetUrlService
     */
    public function __construct(private AccessControlService $accessControlService, private TakeAPhotoService $photoUpdater, private MediaFileRepositoryInterface $mediaFileRepository, private AssetUrlServiceInterface $assetUrlService)
    {
    }

    /**
     * Handles the CreatePrintedProductCommand.
     *
     * @param TakePhotoCommand $command The command information for uploading photo
     *
     * @return string|null The url of stored product photo
     *
     * @throws \InvalidArgumentException If access control is not granted
     */
    public function __invoke(TakePhotoCommand $command): ?string
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');

        $this->photoUpdater->upload(productId: $command->productId, photoId: $command->photoId);

        $photoMediaFile = $command->photoId ? $this->mediaFileRepository->findById($command->photoId) : null;

        return $photoMediaFile ? $this->assetUrlService->getLink($photoMediaFile->getPath()) : null;
    }
}
