<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Api;

use App\ProductionProcess\Infrastructure\Adapter\Employee\EmployeeAdapterInterface;
use App\Users\Application\UseCase\PrivateUseCaseInteractor;

final readonly class EmployeeApi implements EmployeeAdapterInterface
{
    public function __construct(private PrivateUseCaseInteractor $privateUseCaseInteractor)
    {
    }

    public function fetchById(string $employeeId): \App\Users\Application\DTO\UserDTO
    {
        return $this->privateUseCaseInteractor->findAUser($employeeId)->user;
    }

    public function fetchByIds(array $employeeIds = []): array
    {
        $query = new \App\Users\Application\UseCase\Query\FindUsers\FindUsersQuery(ids: $employeeIds);

        return $this->privateUseCaseInteractor->findUsers($query)->items;
    }
}
