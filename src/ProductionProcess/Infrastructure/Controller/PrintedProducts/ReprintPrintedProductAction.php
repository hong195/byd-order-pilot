<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\PrintedProducts;

use App\ProductionProcess\Application\UseCase\Command\ReprintPrintedProduct\ReprintPrintedProductCommand;
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
#[Route('/api/printed-products/{printedProductId}/reprint', name: 'reprint-printed-products', requirements: ['printedProductId' => '^\d+$'], methods: ['POST'])]
final readonly class ReprintPrintedProductAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Invokes the command to reprint a printed product.
     *
     * @param int $printedProductId the ID of the printed product
     *
     * @return JsonResponse a JSON response indicating the success of the operation
     */
    public function __invoke(int $printedProductId, Request $request): JsonResponse
    {
        $command = new ReprintPrintedProductCommand(
            printedProductId: $printedProductId,
            process: $request->get('process'),
            reason: $request->get('reason')
        );

        $this->privateCommandInteractor->reprintPrintedProduct($command);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
