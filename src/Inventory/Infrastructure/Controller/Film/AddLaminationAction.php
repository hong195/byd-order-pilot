<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Controller\Film;

use App\Inventory\Application\UseCases\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/films/laminations', name: 'add_lamination_film', methods: ['POST'])]
final readonly class AddLaminationAction
{
    public function __construct(private PrivateCommandInteractor $commandInteractor)
    {
    }

    /**
     * Handles the HTTP request and adds a roll film.
     *
     * @param Request $request the HTTP request object
     *
     * @return JsonResponse the JSON response containing the ID of the added roll film
     *
     * @throws \Exception if there is an error adding the roll film
     */
    public function __invoke(Request $request): JsonResponse
    {
        $id = $this->commandInteractor->addALamination(
            name: $request->request->get('name'),
            length: (int) $request->request->get('length'),
            type: $request->request->get('type')
        );

        return new JsonResponse([
            'id' => $id,
        ], Response::HTTP_CREATED);
    }
}
