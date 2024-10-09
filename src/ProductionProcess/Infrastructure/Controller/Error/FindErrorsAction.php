<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Error;

use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\ProductionProcess\Application\UseCase\Query\FindErrors\FindErrorsQuery;
use App\Shared\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Controller to find a roll by ID.
 *
 * @Route("/api/rolls/{id}", requirements={"id"="\d+"}, methods={"GET"})
 */
#[AsController]
#[Route('/api/errors', name: 'find_errors_list', methods: ['GET'])]
final class FindErrorsAction extends BaseController
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
     * Invokable method that handles a request to find errors.
     *
     * @param Request $request The HTTP request object containing query parameters
     *
     * @return JsonResponse The JSON response containing the result of finding errors
     */
    public function __invoke(Request $request): JsonResponse
    {
        $query = new FindErrorsQuery(
            process: $request->query->get('process'),
            responsibleEmployeeId: (int) $request->query->get('responsibleEmployeeId'),
            noticerId: (int) $request->query->get('noticerId')
        );
        $result = $this->privateQueryInteractor->findErrors($query);

        $result = $this->normalizer->normalize($result);

        return $this->json($result, Response::HTTP_OK, []);
    }
}
