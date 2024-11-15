<?php

/**
 * Controller to handle roll history statistics fetch requests.
 *
 * This controller responds to GET requests at the endpoint '/api/history-statistics' to fetch roll history statistics based
 * on specified filter criteria, including employee ID, date range, and process type.
 *
 * @throws \DateMalformedStringException if the provided date strings cannot be parsed into valid DateTimeImmutable objects
 */

namespace App\ProductionProcess\Infrastructure\Controller\Rolls\History;

use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\ProductionProcess\Domain\Repository\Statistics\RollHistory\FetchRollHistoryStatisticsFilter;
use App\ProductionProcess\Domain\ValueObject\Process;
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
#[Route('/api/rolls/history-statistics', name: 'fetch_history_statistics', methods: ['GET'])]
readonly class FindRollHistoryStatistics
{
    /**
     * @param PrivateQueryInteractor $privateQueryInteractor
     *
     * @param NormalizerInterface $normalizer
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
        $employeeId = $request->query->get('employeeId');
        $from = $request->query->get('from') ? new \DateTimeImmutable($request->query->get('from')) : null;
        $to = $request->query->get('to') ? new \DateTimeImmutable($request->query->get('to')) : null;
        $process = $request->query->get('process');

        $filter = new FetchRollHistoryStatisticsFilter(
            employeeId: $employeeId,
            process: $process ? Process::from($process) : null,
            from: $from,
            to: $to
        );

        $result = $this->privateQueryInteractor->fetchRollHistoryStatistics($filter);

        $normalizedResult = $this->normalizer->normalize($result);

        return new JsonResponse($normalizedResult);
    }
}
