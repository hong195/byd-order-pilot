<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\UseCase\Query\FindAnOrder\FindAnOrderQuery;
use App\Orders\Application\UseCase\Query\FindAnOrder\FindAnOrderResult;
use App\Orders\Application\UseCase\Query\FindExtras\FindExtrasQuery;
use App\Orders\Application\UseCase\Query\FindExtras\FindExtrasResult;
use App\Orders\Application\UseCase\Query\FindOrders\FindOrdersQuery;
use App\Orders\Application\UseCase\Query\FindOrders\FindOrdersResult;
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
}
