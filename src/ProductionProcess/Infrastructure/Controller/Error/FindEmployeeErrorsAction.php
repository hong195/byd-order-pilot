<?php

declare(strict_types=1);

/**
 * Controller to find a roll by ID.
 *
 * @Route("/api/rolls/{id}", requirements={"id"="\d+"}, methods={"GET"})
 */

namespace App\ProductionProcess\Infrastructure\Controller\Error;

use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\Shared\Domain\Repository\DateRangeFilter;
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
#[Route('/api/employee-errors', name: 'find_employee_errors_list', methods: ['GET'])]
final class FindEmployeeErrorsAction extends BaseController
{
    /**
     * Class constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor The private query interactor object
     * @param NormalizerInterface    $normalizer
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
     *
     * @throws \DateMalformedStringException|\Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function __invoke(Request $request): JsonResponse
    {
        $from = $request->query->get('from') ? new \DateTimeImmutable($request->query->get('from')) : null;
        $to = $request->query->get('to') ? new \DateTimeImmutable($request->query->get('to')) : null;

        $dateRangeFilter = new DateRangeFilter(
            from: $from,
            to: $to
        );

        $result = $this->privateQueryInteractor->findEmployeeErrors($dateRangeFilter);

        $result = $this->normalizer->normalize($result);

        return $this->json($result, Response::HTTP_OK, []);
    }
}
