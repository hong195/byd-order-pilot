<?php

declare(strict_types=1);


namespace App\Rolls\Infrastructure\Repository;

use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Repository\LaminationRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderRepository extends ServiceEntityRepository
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
}