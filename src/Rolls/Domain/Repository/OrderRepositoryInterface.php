<?php

namespace App\Rolls\Domain\Repository;

use App\Rolls\Domain\Aggregate\Order\Order;
use App\Shared\Domain\Repository\PaginationResult;

interface OrderRepositoryInterface
{
	public function save(Order $order): void;

	public function findById(int $id): ?Order;

	public function findPaginated(): PaginationResult;
}
