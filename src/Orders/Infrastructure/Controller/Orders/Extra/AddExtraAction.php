<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders\Extra;

use App\Orders\Application\UseCase\Command\CreateExtra\CreateExtraCommand;
use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the add_product API endpoint.
 */
#[AsController]
#[Route('/api/orders/{orderId}/extras', name: 'add_extra', requirements: ['orderId' => '^\d+$'], methods: ['POST'])]
final readonly class AddExtraAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Handles the request to check product in an order.
     *
     * @param int     $orderId the ID of the order
     * @param Request $request the HTTP request object
     *
     * @return JsonResponse a JSON response indicating the success status of the action
     */
    public function __invoke(int $orderId, Request $request): JsonResponse
    {
        $this->privateCommandInteractor->createExtra(new CreateExtraCommand(
            orderId: $orderId,
            name: (string) $request->get('name'),
            orderNumber: $request->get('orderNumber'),
            count: (int) $request->get('count'),
        ));

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
