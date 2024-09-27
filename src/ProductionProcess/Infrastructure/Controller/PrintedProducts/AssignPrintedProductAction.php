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
#[Route('/api/printed-products/{printedProduct}/assign', name: 'assign-printed-product', requirements: ['printedProduct' => '^\d+$'], methods: ['POST'])]
final readonly class AssignPrintedProductAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

	/**
	 * Invokes the command.
	 *
	 * @param int $printedProduct The printed product ID.
	 *
	 * @return JsonResponse The JSON response indicating the success.
	 */
    public function __invoke(int $printedProduct): JsonResponse
    {
        $this->privateCommandInteractor->assignPrintedProduct($printedProduct);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
