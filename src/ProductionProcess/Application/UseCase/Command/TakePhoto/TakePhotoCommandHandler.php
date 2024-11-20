<?php

declare(strict_types=1);

/**
 * Class TakePhotoCommandHandler.
 */

namespace App\ProductionProcess\Application\UseCase\Command\TakePhoto;

use App\ProductionProcess\Domain\Service\PrintedProduct\TakeAPhotoService;
use App\Shared\Application\AccessControll\AccessControlService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Domain\Service\AssertService;

/**
 * Class ManuallyAddOrderCommandHandler.
 */
final readonly class TakePhotoCommandHandler implements CommandHandlerInterface
{
    /**
     * Constructs a new instance of the class.
     *
     * @param AccessControlService $accessControlService the access control service
     * @param TakeAPhotoService    $photoUpdater
     */
    public function __construct(private AccessControlService $accessControlService, private TakeAPhotoService $photoUpdater)
    {
    }

    /**
     * Handles the CreatePrintedProductCommand.
     *
     * @param TakePhotoCommand $command The command information for uploading photo
     *
     * @return void
     *
     * @throws \InvalidArgumentException If access control is not granted
     */
    public function __invoke(TakePhotoCommand $command): void
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');

        $this->photoUpdater->upload(productId: $command->productId, photoId: $command->photoId);
    }
}
