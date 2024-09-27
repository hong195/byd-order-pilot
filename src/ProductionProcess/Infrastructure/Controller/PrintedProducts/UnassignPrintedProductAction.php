<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\PrintedProducts;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/printed-products/{printedProductId}/unassign', name: 'unassign-order', requirements: ['printedProductId' => '^\d+$'], methods: ['POST'])]
final readonly class UnassignPrintedProductAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

	/**
	 * Invokes the command to unassign an order from a printed product.
	 *
	 * @param int $printedProductId The ID of the printed product to unassign the order from.
	 * @return JsonResponse The JSON response indicating the success of the unassignment.
	 */
    public function __invoke(int $printedProductId): JsonResponse
    {
        $this->privateCommandInteractor->unassignPrintedProduct($printedProductId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
