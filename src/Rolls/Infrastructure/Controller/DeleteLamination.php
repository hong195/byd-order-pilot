<?php

declare(strict_types=1);

namespace App\Rolls\Infrastructure\Controller;

use App\Rolls\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/laminations/{id}', name: 'delete_lamination', requirements: ['id' => '\d+$'], methods: ['DELETE'])]
final readonly class DeleteLamination
{
    public function __construct(private PrivateCommandInteractor $commandInteractor)
    {
    }

    /**
     * Deletes a lamination.
     *
     * @param int $id the id of the lamination to delete
     *
     * @return JsonResponse a JSON response with no content, indicating a successful deletion
     */
    public function __invoke(int $id): JsonResponse
    {
        $this->commandInteractor->deleteLamination($id);

        return new JsonResponse([
            'message' => 'Lamination deleted successfully',
        ], Response::HTTP_OK);
    }
}
