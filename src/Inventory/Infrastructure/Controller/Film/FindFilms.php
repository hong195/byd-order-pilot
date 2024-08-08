<?php

declare(strict_types=1);

namespace App\Inventory\Infrastructure\Controller\Film;

use App\Inventory\Application\UseCases\PrivateQueryInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsController]
#[Route('/api/films', name: 'find_single_film', methods: ['GET'])]
final readonly class FindFilms
{
    public function __construct(private PrivateQueryInteractor $queryInteractor, private NormalizerInterface $normalizer)
    {
    }

    /**
     * Handles the HTTP request and adds a roll film.
     *
     * @param Request $request the HTTP request object
     *
     * @return JsonResponse the JSON response containing the ID of the added roll film
     *
     * @throws \Exception         if there is an error adding the roll film
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {
        $result = $this->queryInteractor->findFilms(
            filmType: $request->get('filmType'),
        );

        $result = $this->normalizer->normalize($result->items);

        return new JsonResponse($result, Response::HTTP_CREATED);
    }
}
