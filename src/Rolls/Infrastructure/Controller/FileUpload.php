<?php

declare(strict_types=1);


namespace App\Rolls\Infrastructure\Controller;

use App\Rolls\Domain\Aggregate\Order\Order;
use App\Rolls\Domain\Aggregate\Order\Priority;
use App\Rolls\Domain\Aggregate\Order\ProductType;
use App\Rolls\Infrastructure\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('api/file-upload', 'file-upload', methods: ['POST'])]
final  class FileUpload
{
	public function __construct(private readonly OrderRepository $orderRepository)
	{
	}
	public function __invoke(Request $request): Response
	{
		$order = new Order(
			Priority::HIGH,
			ProductType::PRODUCT,
			123123123123
		);

		$order->setImageFile($request->files->get('printFile'));

		$this->orderRepository->save($order);

		dd($order);
		return new Response('File uploaded');
	}
}