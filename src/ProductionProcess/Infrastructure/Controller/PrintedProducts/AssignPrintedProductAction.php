<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\PrintedProducts;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Domain\Exceptions\UnassignedPrintedProductsException;
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
     * @param int $printedProduct the printed product ID
     *
     * @return JsonResponse the JSON response indicating the success
     */
    public function __invoke(int $printedProduct): JsonResponse
    {
        try {
            $this->privateCommandInteractor->assignPrintedProduct($printedProduct);

            return new JsonResponse(['message' => 'Success', 'unassigned' => []], Response::HTTP_OK);
        } catch (UnassignedPrintedProductsException $e) {
            return new JsonResponse(['message' => 'Failed', 'unassigned' => $e->unassignedPrintedProductIds()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
