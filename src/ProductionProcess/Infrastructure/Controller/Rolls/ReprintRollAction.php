<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Rolls;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\ProductionProcess\Domain\Exceptions\OrderReprintException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/rolls/{rollId}/reprint', name: 'reprint-roll', requirements: ['rollId' => '^\d+$'], methods: ['POST'])]
final readonly class ReprintRollAction
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
     * @return JsonResponse the JSON response containing the success message
     *
     * @throws OrderReprintException
     */
    public function __invoke(int $rollId): JsonResponse
    {
        $this->privateCommandInteractor->reprintRoll($rollId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
