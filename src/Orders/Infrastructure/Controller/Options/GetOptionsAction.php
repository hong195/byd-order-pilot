<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Options;

use App\Orders\Application\UseCase\PrivateQueryInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/options', name: 'get_options', methods: ['GET'])]
final readonly class GetOptionsAction
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
     * Invoke the controller.
     *
     * @return JsonResponse the JSON response
     */
    public function __invoke(): JsonResponse
    {
        $options = $this->privateQueryInteractor->getOptions();

        return new JsonResponse($options->items, Response::HTTP_OK);
    }
}
