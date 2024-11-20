<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase\Command\TakePhoto;

use App\ProductionProcess\Application\DTO\TakePhotoDTO;
use App\ProductionProcess\Application\Service\PrintedProduct\PrintedProductPhotoUpdater;
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
     * @param AccessControlService       $accessControlService the access control service
     * @param PrintedProductPhotoUpdater $photoUpdater
     */
    public function __construct(private AccessControlService $accessControlService, private PrintedProductPhotoUpdater $photoUpdater)
    {
    }

    /**
     * Handles the CreatePrintedProductCommand.
     *
     * @param TakePhotoCommand $command The command information for uploading photo
     *
     * @return string The url of photo
     *
     * @throws \InvalidArgumentException If access control is not granted
     */
    public function __invoke(TakePhotoCommand $command): string
    {
        AssertService::true($this->accessControlService->isGranted(), 'Not allowed to handle resource.');

        $dto = new TakePhotoDTO(
            productId: $command->productId,
            photoId: $command->photoId
        );

        return $this->photoUpdater->upload($dto);
    }
}
