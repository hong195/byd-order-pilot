<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Shared\Application\Query\QueryBusInterface;
use App\Users\Application\UseCase\Query\FindUser\FindUserQuery;
use App\Users\Application\UseCase\Query\FindUser\FindUserQueryResult;
use App\Users\Application\UseCase\Query\FindUsers\FindUsersQuery;
use App\Users\Application\UseCase\Query\FindUsers\FindUsersQueryResult;
use App\Users\Application\UseCase\Query\GetMe\GetMeQuery;
use App\Users\Application\UseCase\Query\GetMe\GetMeQueryResult;

readonly class PrivateUseCaseInteractor
{
    public function __construct(private QueryBusInterface $queryBus)
    {
    }

    public function getMe(): GetMeQueryResult
    {
        return $this->queryBus->execute(new GetMeQuery());
    }

    /**
     * Find users based on a query.
     *
     * @param FindUsersQuery $query the query to find the users
     *
     * @return FindUsersQueryResult the result of the query to find the users
     */
    public function findUsers(FindUsersQuery $query): FindUsersQueryResult
    {
        return $this->queryBus->execute($query);
    }

    /**
     * Find a user by user ID.
     *
     * @param string $userId the ID of the user to find
     *
     * @return FindUsersQueryResult the result of the query to find the user
     */
    public function findAUser(string $userId): FindUserQueryResult
    {
        $command = new FindUserQuery($userId);

        return $this->queryBus->execute($command);
    }
}
