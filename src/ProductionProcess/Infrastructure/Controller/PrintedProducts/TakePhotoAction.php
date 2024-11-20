<?php

declare(strict_types=1);

/**
 * Handles the request to upload a photo for a given product and returns a JSON response with the product ID.
 *
 * @param int     $productId the ID of the product
 * @param Request $request   the HTTP request instance containing the photo file
 *
 * @return JsonResponse the JSON response with the product ID and HTTP status
 */

namespace App\ProductionProcess\Infrastructure\Controller\PrintedProducts;

use App\ProductionProcess\Application\UseCase\Command\TakePhoto\TakePhotoCommand;
use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\Shared\Domain\Service\UploadFileService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class constructor.
 *
 * @param PrivateCommandInteractor $privateCommandInteractor
 * @param UploadFileService        $uploadFileService        the upload file service instance
 */
#[AsController]
#[Route('api/products/{productId}/take-photo', 'take-product-photo', requirements: ['productId' => '^\d+$'], methods: ['POST'])]
final readonly class TakePhotoAction
{
    /**
     * Class constructor.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor
     * @param UploadFileService        $uploadFileService        the upload file service instance
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor, private UploadFileService $uploadFileService)
    {
    }

    /**
     * Handles the request to upload a photo for a given product and returns a JSON response with the product ID.
     *
     * @param int     $productId the ID of the product
     * @param Request $request   the HTTP request instance containing the photo file
     *
     * @return JsonResponse the JSON response with the product ID and HTTP status
     */
    public function __invoke(int $productId, Request $request): JsonResponse
    {
        $photoId = null;

        if ($photo = $request->files->get('photo')) {
            $photoId = $this->uploadFileService->upload($photo);
        }

        $command = new TakePhotoCommand(
            productId: $productId,
            photoId: $photoId
        );

        $photoPath = $this->privateCommandInteractor->takePhoto($command);

        return new JsonResponse(['photo_path' => $photoPath], Response::HTTP_CREATED);
    }
}
