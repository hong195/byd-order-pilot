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
#[Route('api/orders/manually-add-order', 'manually-add-order', methods: ['POST'])]
final readonly class ManualAddOrderAction
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
        $manuallyAddCommand = new ManuallyAddOrderCommand(
            customerName: $request->get('customerName'),
            shippingAddress: $request->get('shippingAddress'),
            customerNotes: $request->get('customerNotes'),
            packagingInstructions: $request->get('packagingInstructions'),
        );

        $orderId = $this->commandBus->execute($manuallyAddCommand);

        return new JsonResponse(['id' => $orderId], Response::HTTP_CREATED);
    }
}
