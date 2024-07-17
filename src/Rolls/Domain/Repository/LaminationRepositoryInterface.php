<?php

namespace App\Rolls\Domain\Repository;

use App\Rolls\Domain\Aggregate\Lamination\Lamination;

interface LaminationRepositoryInterface
{
    public function add(Lamination $lamination): void;

    public function findById(int $id): ?Lamination;

    public function save(Lamination $lamination): void;

    public function remove(Lamination $lamination): void;
}
