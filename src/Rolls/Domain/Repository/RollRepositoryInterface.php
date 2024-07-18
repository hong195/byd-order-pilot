<?php

namespace App\Rolls\Domain\Repository;

use App\Rolls\Domain\Aggregate\Roll\Roll;
use App\Shared\Domain\Repository\PaginationResult;

interface RollRepositoryInterface
{
    public function add(Roll $roll): void;

    public function findById(int $id): ?Roll;

    public function save(Roll $roll): void;

    public function remove(Roll $roll): void;

    public function findPagedItems(int $page): PaginationResult;
}
