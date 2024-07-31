<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/rolls/{id}', name: 'delete_roll', requirements: ['id' => '^\d+$'], methods: ['DELETE'])]
final readonly class DeleteRoll
{
    public function __construct(private PrivateCommandInteractor $commandInteractor)
    {
    }

    public function __invoke(int $id): JsonResponse
    {
        $this->commandInteractor->deleteRoll($id);

        return new JsonResponse([
			'message' => 'Roll deleted successfully'
		], Response::HTTP_OK);
    }
}
