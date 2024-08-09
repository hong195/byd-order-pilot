<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase;

use App\Shared\Application\Query\QueryBusInterface;
use App\Shared\Domain\Repository\Pager;
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
     * Find users based on given criteria.
     *
     * @param int         $page  The page number to retrieve. Default is 1.
     * @param string|null $email The email to search for. Default is null.
     * @param string|null $name  The name to search for. Default is null.
     *
     * @return FindUsersQueryResult the result of the find users query
     */
    public function findUsers(int $page = 1, ?string $email = null, ?string $name = null): FindUsersQueryResult
    {
        $pager = new Pager(page: $page, perPage: 10);

        $command = new FindUsersQuery($pager, $email, $name);

        return $this->queryBus->execute($command);
    }

    /**
     * Find a user by user ID.
     *
     * @param int $userId the ID of the user to find
     *
     * @return FindUsersQueryResult the result of the query to find the user
     */
    public function findAUser(int $userId): FindUserQueryResult
    {
        $command = new FindUserQuery($userId);

        return $this->queryBus->execute($command);
    }
}
