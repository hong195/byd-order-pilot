<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\UseCase\Query\FindAnOrder\FindAnOrderQuery;
use App\Orders\Application\UseCase\Query\FindAnOrder\FindAnOrderResult;
use App\Orders\Application\UseCase\Query\FindARoll\FindARollQuery;
use App\Orders\Application\UseCase\Query\FindARoll\FindARollResult;
use App\Orders\Application\UseCase\Query\FindExtras\FindExtrasQuery;
use App\Orders\Application\UseCase\Query\FindExtras\FindExtrasResult;
use App\Orders\Application\UseCase\Query\FindOrders\FindOrdersQuery;
use App\Orders\Application\UseCase\Query\FindOrders\FindOrdersResult;
use App\Orders\Application\UseCase\Query\FindPrinters\FindPrintersQuery;
use App\Orders\Application\UseCase\Query\FindPrinters\FindPrintersResult;
use App\Orders\Application\UseCase\Query\FindRollHistory\FindRollHistoryQuery;
use App\Orders\Application\UseCase\Query\FindRollHistory\FindRollHistoryResult;
use App\Orders\Application\UseCase\Query\FindRolls\FindRollsQuery;
use App\Orders\Application\UseCase\Query\FindRolls\FindRollsResult;
use App\Orders\Application\UseCase\Query\GetOptions\GetOptionsQuery;
use App\Orders\Application\UseCase\Query\GetOptions\GetOptionsQueryResult;
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
     * Class ExampleClass.
     *
     * @property QueryBusInterface $queryBus An instance of QueryBusInterface.
     */
    public function findAnOrder(int $id): FindAnOrderResult
    {
        $query = new FindAnOrderQuery($id);

        return $this->queryBus->execute($query);
    }

    /**
     * @method FindOrdersResult findOrders()
     */
    public function findOrders(?int $rollId = null, ?string $status = null): FindOrdersResult
    {
        $query = new FindOrdersQuery(rollId: $rollId, status: $status);

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
     * Finds the extras by executing the FindExtrasQuery.
     *
     * @param int $order the order ID
     *
     * @return FindExtrasResult the result of executing the FindExtrasQuery
     */
    public function findExtras(int $order): FindExtrasResult
    {
        return $this->queryBus->execute(new FindExtrasQuery($order));
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
}
