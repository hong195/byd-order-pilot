<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\Query\FindUsers;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\Users\Application\DTO\UserDTO;
use App\Users\Domain\Repository\UserFilter;
use App\Users\Domain\Repository\UserRepositoryInterface;

/**
 * Class FindUsersQueryHandler.
 */
readonly class FindUsersQueryHandler implements QueryHandlerInterface
{
    /**
     * Class MyClass.
     *
     * This class is an example class that demonstrates constructor injection using Symfony.
     */
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Class FindUserHandler.
     *
     * This class is an example handler class that handles the FindUserQuery and returns a FindUserQueryResult.
     */
    public function __invoke(FindUsersQuery $query): FindUsersQueryResult
    {
        $filter = new UserFilter(pager: $query->pager, name: $query->name, email: $query->email);

        $users = $this->userRepository->findByFilter($filter);

        $userDTOs = UserDTO::fromUsersList($users->items);

        return new FindUsersQueryResult($userDTOs, $users->total);
    }
}
