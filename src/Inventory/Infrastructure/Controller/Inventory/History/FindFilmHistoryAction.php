<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Controller\Inventory\History;

use App\Inventory\Application\UseCases\PrivateQueryInteractor;
use App\Inventory\Application\UseCases\Query\FindFilmHistory\FindFilmHistoryQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/inventory/films/history', name: 'find_film_history', methods: ['GET'])]
final readonly class FindFilmHistoryAction
{
    /**
     * Class constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor the private query interactor instance
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor)
    {
    }

    /**
     * Invokes the controller action that handles the request.
     *
     * @param Request $request the request object
     *
     * @return JsonResponse the response object
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $query = new FindFilmHistoryQuery(
            inventoryType: $request->query->get('inventory'),
            filmId: (int) $request->query->get('filmId'),
            event: $request->query->get('event'),
            type: $request->query->get('type'),
            page: (int) $request->query->get('page', 1),
            perPage: (int) $request->query->get('perPage', 10)
        );

        if ($request->query->has('from') && $request->query->has('to')) {
            $query->withInterval([
                new \DateTimeImmutable($request->query->get('from')),
                new \DateTimeImmutable($request->query->get('to')),
            ]);
        }

        $result = $this->privateQueryInteractor->findFilmHistory($query);

        return new JsonResponse($result);
    }
}
