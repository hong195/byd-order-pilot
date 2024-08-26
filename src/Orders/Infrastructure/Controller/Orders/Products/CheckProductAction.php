<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders\Products;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the add_product API endpoint.
 */
#[AsController]
#[Route('/api/orders/{orderId}/products/{productId}/check', name: 'check_product', requirements: ['orderId' => '^\d+$', 'productId' => '^\d+$'], methods: ['POST'])]
final readonly class CheckProductAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Handles the request to check product in an order.
     *
     * @param int     $orderId   the ID of the order
     * @param int     $productId the ID of the product
     * @param Request $request   the HTTP request object
     *
     * @return JsonResponse a JSON response indicating the success status of the action
     */
    public function __invoke(int $orderId, int $productId, Request $request): JsonResponse
    {
        $this->privateCommandInteractor->packProduct(orderId: $orderId, productId: $productId, isPacked: (bool) $request->get('isPacked', false));

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
