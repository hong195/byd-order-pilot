<?php

declare(strict_types=1);


namespace App\Rolls\Infrastructure\Repository;

use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Repository\OrderRepositoryInterface;
use App\Shared\Domain\Repository\PaginationResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

final class OrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Order::class);
	}

	public function save(Order $order): void
	{
		$this->getEntityManager()->persist($order);
		$this->getEntityManager()->flush();
	}

	public function findById(int $id): ?Order
	{
		return $this->find($id);
	}

	public function findPaginated(): PaginationResult
	{
		$qb = $this->createQueryBuilder('o');

		$query = $qb->getQuery();

		$query->setMaxResults(100);
		$paginator = new Paginator($query);

		return new PaginationResult($query->getResult(), $paginator->count());
	}
}