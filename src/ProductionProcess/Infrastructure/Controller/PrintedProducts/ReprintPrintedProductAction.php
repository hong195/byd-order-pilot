<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\PrintedProducts;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use App\Orders\Domain\Exceptions\OrderReprintException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/printed-products/{orderId}/reprint', name: 'reprint-order', requirements: ['orderId' => '^\d+$'], methods: ['POST'])]
final readonly class ReprintPrintedProductAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Invokes the command to reprint an order.
     *
     * @param int $orderId the ID of the order
     *
     * @return JsonResponse the JSON response containing the success message
     *
     * @throws OrderReprintException
     */
    public function __invoke(int $orderId): JsonResponse
    {
        $this->privateCommandInteractor->reprintOrder($orderId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
