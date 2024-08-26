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
#[Route('/api/orders/{orderId}/products', name: 'add_product', requirements: ['orderId' => '^\d+$'], methods: ['POST'])]
final readonly class AddProductAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Handle the request to add a product to an order.
     *
     * @param int     $orderId the ID of the order
     * @param Request $request the request object
     *
     * @return JsonResponse the JSON response indicating the success of the operation
     */
    public function __invoke(int $orderId, Request $request): JsonResponse
    {
        $this->privateCommandInteractor->addProduct(orderId: $orderId, filmType: $request->get('filmType'), laminationType: $request->get('laminationType'));

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
