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
 * This class handles the change-sort-order API endpoint.
 */
#[AsController]
#[Route('/api/products/{productId}/unpack', name: 'unpack-product', requirements: ['productId' => '^\d+$'], methods: ['POST'])]
final readonly class UnPackProductAction
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
    public function __invoke(int $productId, Request $request): JsonResponse
    {
        $this->privateCommandInteractor->unpackProduct(productId: $productId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
