<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/orders/{id}/change-priority', name: 'change-order-priority', requirements: ['id' => '^\d+$'], methods: ['POST'])]
final readonly class ChangeOrderPriorityAction
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
     * @param int     $id      the ID of the order
     * @param Request $request the request object
     *
     * @return JsonResponse the JSON response
     */
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $this->privateCommandInteractor->changeOrderPriority($id, filter_var($request->get('priority'), FILTER_VALIDATE_BOOLEAN));

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
