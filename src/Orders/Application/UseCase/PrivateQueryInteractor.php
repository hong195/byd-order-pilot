<?php

declare(strict_types=1);

namespace App\Orders\Application\UseCase;

use App\Orders\Application\UseCase\Query\FindALamination\FindALaminationQuery;
use App\Orders\Application\UseCase\Query\FindAnOrder\FindAnOrderQuery;
use App\Orders\Application\UseCase\Query\FindAnOrder\FindAnOrderResult;
use App\Orders\Application\UseCase\Query\FindARoll\FindALaminationResult;
use App\Orders\Application\UseCase\Query\FindARoll\FindARollQuery;
use App\Orders\Application\UseCase\Query\FindARoll\FindARollResult;
use App\Orders\Application\UseCase\Query\FindLaminations\FindLaminationsQuery;
use App\Orders\Application\UseCase\Query\FindLaminations\FindLaminationsResult;
use App\Orders\Application\UseCase\Query\FindOrders\FindOrdersQuery;
use App\Orders\Application\UseCase\Query\FindOrders\FindOrdersResult;
use App\Orders\Application\UseCase\Query\FindRolls\FindRollsQuery;
use App\Orders\Application\UseCase\Query\FindRolls\FindRollsResult;
use App\Shared\Application\Query\QueryBusInterface;

/**
 * Class PrivateCommandInteractor.
 */
readonly class PrivateQueryInteractor
{
    /**
     * Class ExampleClass.
     */
    public function __construct(
        private QueryBusInterface $queryBus
    ) {
    }

    /**
     * Finds the rolls using the query bus.
     *
     * @return FindRollsResult the result of finding the rolls
     */
    public function findRolls(): FindRollsResult
    {
        $query = new FindRollsQuery();

        return $this->queryBus->execute($query);
    }

    public function findARoll(int $id): FindARollResult
    {
        $query = new FindARollQuery($id);

        return $this->queryBus->execute($query);
    }

    /**
     * Find a Lamination by ID.
     *
     * @param int $id the ID of the Lamination
     *
     * @return FindALaminationResult the result of the FindALaminationQuery execution
     */
    public function findALamination(int $id): FindALaminationResult
    {
        $query = new FindALaminationQuery($id);

        return $this->queryBus->execute($query);
    }

    /**
     * Find Laminations.
     *
     * @return FindLaminationsResult the result of the FindLaminationsQuery execution
     */
    public function findLaminations(): FindLaminationsResult
    {
        $query = new FindLaminationsQuery();

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
    public function findOrders(): FindOrdersResult
    {
        $query = new FindOrdersQuery();

        return $this->queryBus->execute($query);
    }
}
