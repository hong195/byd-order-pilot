<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\PrintedProducts;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/printed-products/{printedProductId}/change-priority', name: 'change-printed-product-priority', requirements: ['printedProductId' => '^\d+$'], methods: ['POST'])]
final readonly class ChangePrintedProductPriorityAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

	/**
	 * Invokes the command to change the order priority for a printed product.
	 *
	 * @param int $printedProductId The ID of the printed product.
	 * @param Request $request The HTTP request object.
	 *
	 * @return JsonResponse The JSON response object.
	 */
    public function __invoke(int $printedProductId, Request $request): JsonResponse
    {
        $this->privateCommandInteractor->changePrintedProductPriority($printedProductId, filter_var($request->get('priority'), FILTER_VALIDATE_BOOLEAN));

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
