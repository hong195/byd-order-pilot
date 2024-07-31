<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Order;

use App\Orders\Application\UseCase\PrivateQueryInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsController]
#[Route('/api/orders', name: 'find_orders', methods: ['GET'])]
final readonly class FindOrders
{
	public function __construct(private PrivateQueryInteractor $privateQueryInteractor, private NormalizerInterface $normalizer)
	{
	}

	/**
	 * Retrieves orders and returns them as a JSON response.
	 *
	 * @return JsonResponse Returns a JSON response with the normalized orders.
	 * @throws ExceptionInterface
	 */
	public function __invoke(): JsonResponse
	{
		$orders = $this->privateQueryInteractor->findOrders();

		$res = $this->normalizer->normalize($orders);

		return new JsonResponse($res, Response::HTTP_OK);
	}
}
