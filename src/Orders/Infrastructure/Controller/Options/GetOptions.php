<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Options;

use App\Orders\Application\UseCase\PrivateQueryInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

#[AsController]
#[Route('/api/options', name: 'get_options', methods: ['GET'])]
final readonly class GetOptions
{
    /**
     * Constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor the private query interactor instance
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor)
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
        $options = $this->privateQueryInteractor->getOptions();

        return new JsonResponse($options->items, Response::HTTP_OK);
    }
}
