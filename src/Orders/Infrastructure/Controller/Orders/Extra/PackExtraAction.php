<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders\Extra;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the add_product API endpoint.
 */
#[AsController]
#[Route('/api/orders/{orderId}/pack/extras/{extraId}', name: 'pack_extra', requirements: ['orderId' => '^\w+$', 'extraId' => '^\w+$'], methods: ['POST'])]
final readonly class PackExtraAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Handles packing extra for an order.
     *
     * @param string $orderId the ID of the order
     * @param string $extraId the ID of the extra
     *
     * @return JsonResponse the JSON response
     */
    public function __invoke(string $orderId, string $extraId): JsonResponse
    {
        $this->privateCommandInteractor->packExtra(orderId: $orderId, extraId: $extraId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
