<?php

/**
 * @param FetchRollHistoryStatisticsQueryHandler $handler
 */

namespace App\ProductionProcess\Infrastructure\Controller\Rolls\History;

use App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics\FetchRollHistoryStatisticsQuery;
use App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics\FetchRollHistoryStatisticsQueryHandler;
use App\ProductionProcess\Application\UseCase\Query\RollHistoryStatistics\RollHistoryStatisticsFilterCriteria;
use App\ProductionProcess\Domain\ValueObject\Process;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @param FetchRollHistoryStatisticsQueryHandler $handler
 */
#[AsController]
#[Route('/api/roll-history-statistics', name: 'fetch_roll_statistics', methods: ['GET'])]
readonly class RollHistoryStatisticsController
{
    /**
     * @param FetchRollHistoryStatisticsQueryHandler $handler
     */
    public function __construct(private FetchRollHistoryStatisticsQueryHandler $handler)
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

        $processEnum = null;

        if ($process) {
            $processEnum = Process::from($process);
        }

        $criteria = new RollHistoryStatisticsFilterCriteria(
            $employeeId,
            $processEnum,
            $from,
            $to
        );

        $query = new FetchRollHistoryStatisticsQuery($criteria);
        $result = $this->handler->__invoke($query);


        return new JsonResponse($result);
    }
}
