<?php

declare(strict_types=1);

namespace App\Users\Application\UseCase\Query\FindUser;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\Users\Application\DTO\UserDTO;
use App\Users\Domain\Repository\UserRepositoryInterface;

/**
 * Class FindUserQueryHandler.
 *
 * Class that handles the FindUserQuery.
 *
 * */
readonly class FindUserQueryHandler implements QueryHandlerInterface
{
    /**
     * Class that represents a constructor of a certain class.
     *
     * @param UserRepositoryInterface $userRepository Injected instance of UserRepositoryInterface
     */
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Class that represents an invokable method.
     *
     * @param FindUserQuery $query Injected instance of FindUserQuery
     *
     * @return FindUserQueryResult The result of the FindUserQuery invocation
     *
     * @throws \InvalidArgumentException If access is denied
     */
    public function __invoke(FindUserQuery $query): FindUserQueryResult
    {
        $user = $this->userRepository->findById($query->userId);
        $userDTO = $user ? UserDTO::fromEntity($user) : null;

        return new FindUserQueryResult($userDTO);
    }
}
