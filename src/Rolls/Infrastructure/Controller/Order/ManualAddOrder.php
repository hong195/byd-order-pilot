<?php

declare(strict_types=1);

namespace App\Rolls\Infrastructure\Controller\Order;

use App\Rolls\Application\UseCase\Command\ManuallyAddOrder\ManuallyAddOrderCommand;
use App\Shared\Domain\Service\UploadFileService;
use App\Shared\Infrastructure\Bus\CommandBus;
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
     * @return Response the HTTP response object
     */
    public function __invoke(Request $request): Response
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
            priority: $request->get('priority'),
            productType: $request->get('productType'),
            laminationType: $request->get('laminationType'),
            rollType: $request->get('rollType'),
            orderNumber: $request->get('orderNumber'),
            cutFileId: $cutFileId,
            printFileId: $printFileId
        );

        $this->commandBus->execute($manuallyAddCommand);

        return new Response('File uploaded');
    }
}
