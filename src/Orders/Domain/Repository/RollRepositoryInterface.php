<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Roll\Roll;
use App\Shared\Domain\Repository\PaginationResult;

interface RollRepositoryInterface
{
    public function add(Roll $roll): void;

    public function findById(int $id): ?Roll;

    public function save(Roll $roll): void;

    public function remove(Roll $roll): void;

    public function findPagedItems(int $page): PaginationResult;
}
