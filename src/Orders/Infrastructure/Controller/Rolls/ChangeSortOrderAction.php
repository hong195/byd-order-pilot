<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Rolls;

use App\Orders\Application\DTO\SortOrderData;
use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the change-sort-order API endpoint.
 */
#[AsController]
#[Route('/api/rolls/{rollId}/orders/change-sort', name: 'change-sort-order', requirements: ['rollId' => '^\d+$'], methods: ['POST'])]
final readonly class ChangeSortOrderAction
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
     * @return JsonResponse the JSON response
     */
    public function __invoke(int $rollId, Request $request): JsonResponse
    {
        $sortOrderData = new SortOrderData(rollId: $rollId, group: (int) $request->get('group'), sortOrders: array_map('intval', $request->get('sortOrders')));
        $this->privateCommandInteractor->changeSortOrder($sortOrderData);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
