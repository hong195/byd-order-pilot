<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders\Products;

use App\Orders\Application\UseCase\Command\AddProduct\AddProductCommand;
use App\Shared\Domain\Service\UploadFileService;
use App\Shared\Infrastructure\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('api/orders/{orderId}/products', 'add-product', requirements: ['orderId' => '^\d+$'], methods: ['POST'])]
final readonly class AddProductAction
{
	/**
	 * Class constructor.
	 *
	 * @param CommandBus $commandBus the command bus instance
	 * @param UploadFileService $uploadFileService the upload file service instance
	 */
	public function __construct(private CommandBus $commandBus, private UploadFileService $uploadFileService)
	{
	}

	/**
	 * Handles a request to upload files and add an order.
	 *
	 * @param Request $request the HTTP request object
	 *
	 * @return JsonResponse the HTTP response object
	 */
	public function __invoke(int $orderId, Request $request): JsonResponse
	{
		$cutFileId = null;
		$printFileId = null;

		if ($cutFile = $request->files->get('cutFile')) {
			$cutFileId = $this->uploadFileService->upload($cutFile);
		}

		if ($printFile = $request->files->get('printFile')) {
			$printFileId = $this->uploadFileService->upload($printFile);
		}

		$productDTO = new AddProductCommand(
			orderId: $orderId,
			length: (int) $request->get('length'),
            filmType: $request->get('filmType'),
            laminationType: $request->get('laminationType'),
            cutFileId: $cutFileId,
            printFileId: $printFileId,
        );

        $productId = $this->commandBus->execute($productDTO);

        return new JsonResponse(['id' => $productId], Response::HTTP_CREATED);
    }
}
