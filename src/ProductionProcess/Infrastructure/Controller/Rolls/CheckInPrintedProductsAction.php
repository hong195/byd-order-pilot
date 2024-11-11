<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Rolls;

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
#[Route('/api/check-in-printed-products', name: 'check_in_printed_products', methods: ['POST'])]
final readonly class CheckInPrintedProductsAction
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
    public function __invoke(Request $request): JsonResponse
    {
        $ids = array_map('intval', $request->get('printedProductsIds'));
        $unassignedPrintedProductIds = $this->privateCommandInteractor->checkInPrintedProducts(printedProducts: $ids)->unassignedPrintedProductIds;

        if (!empty($unassignedPrintedProductIds)) {
            return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
        }

        return new JsonResponse(['message' => 'Failed', 'unassigned' => $unassignedPrintedProductIds], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
