<?php

namespace App\Orders\Domain\Repository;

use App\Orders\Domain\Aggregate\Lamination\Lamination;

interface LaminationRepositoryInterface
{
    public function add(Lamination $lamination): void;

    public function findById(int $id): ?Lamination;

    public function save(Lamination $lamination): void;

    public function remove(Lamination $lamination): void;
}
