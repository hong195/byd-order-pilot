<?php

declare(strict_types=1);

namespace App\ProductionProcess\Application\UseCase;

use App\ProductionProcess\Application\UseCase\Query\FindARoll\FindARollQuery;
use App\ProductionProcess\Application\UseCase\Query\FindARoll\FindARollResult;
use App\ProductionProcess\Application\UseCase\Query\FindPrintedProducts\FindPrintedProductsQuery;
use App\ProductionProcess\Application\UseCase\Query\FindPrintedProducts\FindPrintedProductsQueryResult;
use App\ProductionProcess\Application\UseCase\Query\FindPrinters\FindPrintersQuery;
use App\ProductionProcess\Application\UseCase\Query\FindPrinters\FindPrintersResult;
use App\ProductionProcess\Application\UseCase\Query\FindRollHistory\FindRollHistoryQuery;
use App\ProductionProcess\Application\UseCase\Query\FindRollHistory\FindRollHistoryResult;
use App\ProductionProcess\Application\UseCase\Query\FindRolls\FindRollsQuery;
use App\ProductionProcess\Application\UseCase\Query\FindRolls\FindRollsResult;
use App\ProductionProcess\Application\UseCase\Query\GetOptions\GetOptionsQuery;
use App\ProductionProcess\Application\UseCase\Query\GetOptions\GetOptionsQueryResult;
use App\Shared\Application\Query\QueryBusInterface;

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

    /**
     * Retrieves the options by executing the GetOptionsQuery.
     *
     * @return GetOptionsQueryResult the result of executing the GetOptionsQuery
     */
    public function getOptions(): GetOptionsQueryResult
    {
        return $this->queryBus->execute(new GetOptionsQuery());
    }

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
}
