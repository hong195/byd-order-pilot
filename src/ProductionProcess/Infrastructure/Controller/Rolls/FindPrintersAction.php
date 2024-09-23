<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Rolls;

use App\ProductionProcess\Application\UseCase\PrivateQueryInteractor;
use App\Shared\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class constructor.
 *
 * @param PrivateQueryInteractor $privateQueryInteractor The private query interactor object
 * @param NormalizerInterface    $normalizer             The normalizer interface object
 */
#[AsController]
#[Route('/api/printers', name: 'find_printers_list', methods: ['GET'])]
final class FindPrintersAction extends BaseController
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
     * Calls the findARoll method of the privateQueryInteractor to retrieve RollData by id.
     *
     * @throws ExceptionInterface
     */
    public function __invoke(): JsonResponse
    {
        $result = $this->privateQueryInteractor->findPrinters();

        $result = $this->normalizer->normalize($result);

        return $this->json($result, Response::HTTP_OK, []);
    }
}
