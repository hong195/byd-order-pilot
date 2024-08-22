<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders;

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
#[Route('/api/rolls/{rollId}/reprint/orders/{orderId}', name: 'change-sort-order', requirements: ['rollId' => '^\d+$', 'orderId' => '^\d+$'], methods: ['POST'])]
final readonly class ReprintOrderAction
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
     * @param int $rollId  the ID of the roll
     * @param int $orderId the ID of the order
     *
     * @return JsonResponse the JSON response containing the success message
     *
     * @throws OrderReprintException
     */
    public function __invoke(int $rollId, int $orderId): JsonResponse
    {
        $this->privateCommandInteractor->reprintOrder($rollId, $orderId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
