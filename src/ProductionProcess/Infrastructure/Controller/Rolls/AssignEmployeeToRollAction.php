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
#[Route('/api/rolls/{rollId}/assign-employee', name: 'assign_employee_to_roll', requirements: ['rollId' => '^\w+$'], methods: ['POST'])]
final readonly class AssignEmployeeToRollAction
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
    public function __invoke(string $rollId, Request $request): JsonResponse
    {
        $this->privateCommandInteractor->assignEmployeeToARoll($rollId, (int) $request->get('employeeId'));

        return new JsonResponse(['message' => 'Success'], Response::HTTP_OK);
    }
}
