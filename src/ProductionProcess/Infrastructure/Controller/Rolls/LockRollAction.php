<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Rolls;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/rolls/{rollId}/lock', name: 'lock-roll', requirements: ['rollId' => '^\w+$'], methods: ['POST'])]
final readonly class LockRollAction
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Invokes the requested action.
     *
     * @param string $rollId the unique identifier of the roll to be locked
     *
     * @return JsonResponse a JSON response indicating the success status after locking the specified roll
     */
    public function __invoke(string $rollId): JsonResponse
    {
        $this->privateCommandInteractor->lockRoll($rollId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
