<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Rolls;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the change-sort-order API endpoint.
 */
#[AsController]
#[Route('/api/rolls/{rollId}/pack/orders/{orderId}', name: 'pack-main-product', requirements: ['rollId' => '^\d+$', 'orderId' => '^\d+$'], methods: ['POST'])]
final readonly class PackMainProductAction
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
    public function __invoke(int $rollId, int $orderId): JsonResponse
    {
        $this->privateCommandInteractor->packMainProduct($rollId, $orderId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
