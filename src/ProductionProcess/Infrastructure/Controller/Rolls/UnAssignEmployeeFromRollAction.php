<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Rolls;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This class handles the change-sort-order API endpoint.
 */
#[AsController]
#[Route('/api/rolls/{rollId}/unassign-employee', name: 'unassign_employee_to_roll', requirements: ['rollId' => '^\d+$'], methods: ['POST'])]
final readonly class UnAssignEmployeeFromRollAction
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
    public function __invoke(int $rollId, Request $request): JsonResponse
    {
        $this->privateCommandInteractor->unassignEmployeeFromRoll($rollId);

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
