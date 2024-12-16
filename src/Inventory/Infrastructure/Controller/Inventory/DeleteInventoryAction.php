<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Controller\Inventory;

use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/inventory/{id}', name: 'delete_inventory', requirements: ['id' => '^\w+$'], methods: ['DELETE'])]
final readonly class DeleteInventoryAction
{
    /**
     * Constructor of the class.
     *
     * @param PrivateCommandInteractor $commandInteractor the private command interactor object
     */
    public function __construct(private PrivateCommandInteractor $commandInteractor)
    {
    }

    /**
     * Deletes a film from the database.
     *
     * @param string $id the ID of the film to delete
     *
     * @return JsonResponse a JSON response indicating success or failure
     */
    public function __invoke(string $id): JsonResponse
    {
        $this->commandInteractor->deleteFilm(id: $id);

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
