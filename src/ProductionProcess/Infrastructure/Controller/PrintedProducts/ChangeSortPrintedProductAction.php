<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\PrintedProducts;

use App\Orders\Application\DTO\Order\SortData;
use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the change-sort-order API endpoint.
 */
#[AsController]
#[Route('/api/rolls/{rollId}/printed-products/change-sort', name: 'change-printed-product-order', requirements: ['rollId' => '^\w+$'], methods: ['POST'])]
final readonly class ChangeSortPrintedProductAction
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
    public function __invoke(string $rollId, Request $request): JsonResponse
    {
        $sortOrderData = new SortData(rollId: $rollId, group: (int) $request->get('group'), sortOrders: array_map('intval', $request->get('sortOrders')));
        $this->privateCommandInteractor->changeSortOrder($sortOrderData);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
