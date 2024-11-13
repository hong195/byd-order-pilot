<?php

namespace App\ProductionProcess\Infrastructure\Controller\Rolls\History;

use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\ProductionProcess\Domain\Repository\FetchRollHistoryStatisticsFilter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Handles the request to fetch history statistics based on provided filter criteria.
 *
 * @param Request $request the HTTP request instance containing query parameters
 *
 * @return JsonResponse the JSON response with the fetched history statistics
 *
 * @throws \DateMalformedStringException if the provided date strings are malformed
 */
#[AsController]
#[Route('/api/rolls/employer-roll-count', name: 'fetch_employer_roll_count', methods: ['GET'])]
readonly class FindEmployerRollCountStatistics
{
    /**
     * @param PrivateQueryInteractor $privateQueryInteractor
     *
     * @param NormalizerInterface    $normalizer
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor, private NormalizerInterface $normalizer)
    {
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws \DateMalformedStringException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $from = $request->query->get('from') ? new \DateTimeImmutable($request->query->get('from')) : null;
        $to = $request->query->get('to') ? new \DateTimeImmutable($request->query->get('to')) : null;

        $filter = new FetchRollHistoryStatisticsFilter(
            from: $from,
            to: $to
        );

        $result = $this->privateQueryInteractor->fetchEmployerRollCountStatistics(filter: $filter);

        $normalizedResult = $this->normalizer->normalize($result);

        return new JsonResponse($normalizedResult);
    }
}
