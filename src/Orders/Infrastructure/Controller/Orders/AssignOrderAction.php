<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/orders/{id}/assign', name: 'assign-order', requirements: ['id' => '^\d+$'], methods: ['POST'])]
final readonly class AssignOrderAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Invokes the command to change the order status.
     *
     * @param int $id the ID of the order
     *
     * @return JsonResponse the JSON response
     */
    public function __invoke(int $id): JsonResponse
    {
        $this->privateCommandInteractor->assignOrder($id);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
