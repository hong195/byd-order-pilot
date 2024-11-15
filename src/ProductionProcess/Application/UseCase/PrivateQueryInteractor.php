<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase;

use App\ProductionProcess\Application\UseCase\Query\FetchEmployerRollCountStatistics\FetchEmployeeRollCountStatisticsQuery;
use App\ProductionProcess\Application\UseCase\Query\FetchEmployerRollCountStatistics\FetchEmployeeRollCountStatisticsResult;
use App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics\FetchRollHistoryStatisticsQuery;
use App\ProductionProcess\Application\UseCase\Query\FetchRollHistoryStatistics\FetchRollHistoryStatisticsResult;
use App\ProductionProcess\Application\UseCase\Query\FindARoll\FindARollQuery;
use App\ProductionProcess\Application\UseCase\Query\FindARoll\FindARollResult;
use App\ProductionProcess\Application\UseCase\Query\FindEmployerErrors\FindEmployeeErrorsQuery;
use App\ProductionProcess\Application\UseCase\Query\FindEmployerErrors\FindEmployeeErrorsResult;
use App\ProductionProcess\Application\UseCase\Query\FindErrors\FindErrorsQuery;
use App\ProductionProcess\Application\UseCase\Query\FindErrors\FindErrorsResult;
use App\ProductionProcess\Application\UseCase\Query\FindPrintedProduct\FindPrintedProductQuery;
use App\ProductionProcess\Application\UseCase\Query\FindPrintedProduct\FindPrintedProductQueryResult;
use App\ProductionProcess\Application\UseCase\Query\FindPrintedProducts\FindPrintedProductsQuery;
use App\ProductionProcess\Application\UseCase\Query\FindPrintedProducts\FindPrintedProductsQueryResult;
use App\ProductionProcess\Application\UseCase\Query\FindPrinters\FindPrintersQuery;
use App\ProductionProcess\Application\UseCase\Query\FindPrinters\FindPrintersResult;
use App\ProductionProcess\Application\UseCase\Query\FindRollHistory\FindRollHistoryQuery;
use App\ProductionProcess\Application\UseCase\Query\FindRollHistory\FindRollHistoryResult;
use App\ProductionProcess\Application\UseCase\Query\FindRolls\FindRollsQuery;
use App\ProductionProcess\Application\UseCase\Query\FindRolls\FindRollsResult;
use App\ProductionProcess\Application\UseCase\Query\GetPrintedProductsProcessDetail\GetPrintedProductsProcessDetailQuery;
use App\ProductionProcess\Application\UseCase\Query\GetPrintedProductsProcessDetail\GetPrintedProductsProcessDetailResult;
use App\ProductionProcess\Domain\Repository\RollHistory\FetchRollHistoryStatisticsFilter;
use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Repository\DateRangeFilter;

// use App\ProductionProcess\Application\UseCase\Query\GetOptions\GetOptionsQuery;
// use App\ProductionProcess\Application\UseCase\Query\GetOptions\GetOptionsQueryResult;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateQueryInteractor
{
    /**
     * Class ExampleClass.
     */
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    /**
     * Finds the rolls using the query bus.
     *
     * @return FindRollsResult the result of finding the rolls
     */
    public function findRolls(?string $process = null, ?string $filmType = null, ?string $laminationType = null): FindRollsResult
    {
        $query = new FindRollsQuery($process, $filmType, $laminationType);

        return $this->queryBus->execute($query);
    }

    /**
     * Find a roll by ID.
     *
     * @param int $id the roll ID to find
     *
     * @return FindARollResult the result of the query
     */
    public function findARoll(int $id): FindARollResult
    {
        $query = new FindARollQuery($id);

        return $this->queryBus->execute($query);
    }

    /**
     * Finds printers.
     *
     * @return FindPrintersResult the result of finding printers
     */
    public function findPrinters(): FindPrintersResult
    {
        return $this->queryBus->execute(new FindPrintersQuery());
    }

    //    /**
    //     * Retrieves the options by executing the GetOptionsQuery.
    //     *
    //     * @return GetOptionsQueryResult the result of executing the GetOptionsQuery
    //     */
    //    public function getOptions(): GetOptionsQueryResult
    //    {
    //        return $this->queryBus->execute(new GetOptionsQuery());
    //    }

    /**
     * Retrieves the roll history by executing the FindRollHistoryQuery.
     *
     * @param int $rollId the roll ID to find the history for
     *
     * @return FindRollHistoryResult the result of executing the FindRollHistoryQuery
     */
    public function findRollHistory(int $rollId): FindRollHistoryResult
    {
        return $this->queryBus->execute(new FindRollHistoryQuery($rollId));
    }

    public function findPrintedProductByRollId(int $rollId): FindPrintedProductsQueryResult
    {
        $query = new FindPrintedProductsQuery(rollId: $rollId);

        return $this->queryBus->execute($query);
    }

    public function findUnassignedPrintedProducts(): FindPrintedProductsQueryResult
    {
        $query = new FindPrintedProductsQuery(unassigned: true);

        return $this->queryBus->execute($query);
    }

    /**
     * Finds errors by executing the given FindErrorsQuery.
     *
     * @param FindErrorsQuery $query the query object containing the search criteria
     *
     * @return FindErrorsResult the result of executing the query
     */
    public function findErrors(FindErrorsQuery $query): FindErrorsResult
    {
        return $this->queryBus->execute($query);
    }

    /**
     * Finds errors by executing the given FindErrorsQuery.
     *
     * @param DateRangeFilter $dateRangeFilter the query object containing the date range filter
     *
     * @return FindEmployeeErrorsResult the result of executing the query
     */
    public function findEmployeeErrors(DateRangeFilter $dateRangeFilter): FindEmployeeErrorsResult
    {
        $query = new FindEmployeeErrorsQuery(dateRangeFilter: $dateRangeFilter);

        return $this->queryBus->execute($query);
    }

    /**
     * Retrieves the printed product process detail by executing the GetPrintedProductsProcessDetailQuery.
     *
     * @param int[] $relatedProductsIds An array of related product IDs
     *
     * @return GetPrintedProductsProcessDetailResult the result of executing the GetPrintedProductsProcessDetailQuery
     */
    public function getPrintedProductProcessDetail(array $relatedProductsIds): GetPrintedProductsProcessDetailResult
    {
        return $this->queryBus->execute(new GetPrintedProductsProcessDetailQuery($relatedProductsIds));
    }

    /**
     * Finds a printed product by executing the FindPrintedProductQuery with the provided printed product ID.
     *
     * @param int $printedProductId The ID of the printed product to find
     *
     * @return FindPrintedProductQueryResult The result of executing the FindPrintedProductQuery
     */
    public function findPrintedProduct(int $printedProductId): FindPrintedProductQueryResult
    {
        return $this->queryBus->execute(new FindPrintedProductQuery($printedProductId));
    }

    public function fetchRollHistoryStatistics(FetchRollHistoryStatisticsFilter $filter): FetchRollHistoryStatisticsResult
    {
        $query = new FetchRollHistoryStatisticsQuery($filter);

        return $this->queryBus->execute($query);
    }

    public function fetchEmployerRollCountStatistics(DateRangeFilter $dateRangeFilter): FetchEmployeeRollCountStatisticsResult
    {
        $query = new FetchEmployeeRollCountStatisticsQuery(dateRangeFilter: $dateRangeFilter);

        return $this->queryBus->execute($query);
    }
}
