<?php

declare(strict_types=1);

namespace App\Rolls\Infrastructure\Controller\Order;

use App\Rolls\Application\UseCase\PrivateQueryInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * This class handles the find_order API endpoint.
 */
#[AsController]
#[Route('/api/orders/{id}', name: 'find_order', requirements: ['id' => '^\d+$'], methods: ['GET'])]
final readonly class FindOrder
{
    /**
     * Class constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor The private query interactor object
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor, private NormalizerInterface $normalizer)
    {
    }

    /**
     * Invokes the method when the object is called.
     *
     * @param int $id The ID of the order
     *
     * @return JsonResponse The response object
     */
    public function __invoke(int $id): JsonResponse
    {
        $result = $this->privateQueryInteractor->findOrder($id);

        $result = $this->normalizer->normalize($result);

        return new JsonResponse($result['orderData'], Response::HTTP_OK);
    }
}
