<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Controller\Inventory;

use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/inventory/films/{id}', name: 'update_inventory_film', requirements: ['id' => '^\d+$'], methods: ['PUT'])]
final readonly class UpdateInventoryFilmAction
{
    /**
     * Constructor for the class.
     *
     * @param PrivateCommandInteractor $commandInteractor a instance of the PrivateCommandInteractor class
     */
    public function __construct(private PrivateCommandInteractor $commandInteractor)
    {
    }

    /**
     * Invokes the command interactor to update a film.
     *
     * @param int     $id      The ID of the film to be updated
     * @param Request $request The request object containing the updated film data
     *
     * @return JsonResponse The JSON response indicating a successful film update
     */
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $this->commandInteractor->updateFilm(
            id: $id,
            name: $request->request->get('name'),
            length: (int) $request->request->get('length'),
            type: $request->request->get('type'),
        );

        return new JsonResponse([], Response::HTTP_OK);
    }
}
