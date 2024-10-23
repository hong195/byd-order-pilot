<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Orders\Products;

use App\Orders\Application\UseCase\PrivateQueryInteractor;
use App\Orders\Application\UseCase\Query\FindProducts\FindProductsQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[AsController]
#[Route('/api/orders/{orderId}/products', name: 'find_products', requirements: ['orderId' => '^\d+$'], methods: ['GET'])]
final readonly class FindProducts
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
    public function __invoke(int $orderId): JsonResponse
    {
        $products = $this->privateQueryInteractor->findProducts(new FindProductsQuery(orderId: $orderId));

        $res = $this->normalizer->normalize($products);

        return new JsonResponse($res, Response::HTTP_OK);
    }
}
