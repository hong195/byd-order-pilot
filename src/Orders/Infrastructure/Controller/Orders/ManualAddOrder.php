<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders;

use App\Orders\Application\UseCase\Command\ManuallyAddOrder\ManuallyAddOrderCommand;
use App\Shared\Domain\Service\UploadFileService;
use App\Shared\Infrastructure\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('api/manually-add-order', 'manually-add-order', methods: ['POST'])]
final readonly class ManualAddOrder
{
    /**
     * Class constructor.
     *
     * @param CommandBus        $commandBus        the command bus instance
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
    public function __invoke(Request $request): JsonResponse
    {
        $cutFileId = null;
        $printFileId = null;

        if ($request->files->has('cutFile')) {
            $cutFileId = $this->uploadFileService->upload($request->files->get('cutFile'));
        }

        if ($request->files->has('printFile')) {
            $printFileId = $this->uploadFileService->upload($request->files->get('printFile'));
        }

        $manuallyAddCommand = new ManuallyAddOrderCommand(
            productType: $request->get('productType'),
            length: (int) $request->get('length'),
            filmType: $request->get('filmType'),
            hasPriority: (bool) $request->get('hasPriority'),
            laminationType: $request->get('laminationType'),
            orderNumber: $request->get('orderNumber'),
            cutFileId: $cutFileId,
            printFileId: $printFileId
        );

        $orderId = $this->commandBus->execute($manuallyAddCommand);

        return new JsonResponse(['id' => $orderId], Response::HTTP_CREATED);
    }
}
