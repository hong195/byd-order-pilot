<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders;

use App\Orders\Application\UseCase\PrivateQueryInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @return JsonResponse returns a JSON response with the normalized orders
     *
     * @throws ExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {
        $orders = $this->privateQueryInteractor->findOrders((int) $request->get('rollId'));

        $res = $this->normalizer->normalize($orders);

        return new JsonResponse($res, Response::HTTP_OK);
    }
}
