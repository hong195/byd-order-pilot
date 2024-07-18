<?php

declare(strict_types=1);

namespace App\Rolls\Application\UseCase;

use App\Rolls\Application\DTO\RollData;
use App\Rolls\Application\UseCase\Query\FindARoll\FindARollQuery;
use App\Rolls\Application\UseCase\Query\FindARoll\FindARollResult;
use App\Rolls\Application\UseCase\Query\FindRolls\FindRollsQuery;
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
     * @return RollData[]
     */
    public function findRolls(): array
    {
        $query = new FindRollsQuery();

        return $this->queryBus->execute($query);
    }

    /**
     * Class ExampleClass.
     */
    public function findARoll(int $id): FindARollResult
    {
        $query = new FindARollQuery($id);

        return $this->queryBus->execute($query);
    }
}
