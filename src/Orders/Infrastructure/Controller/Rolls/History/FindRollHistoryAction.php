<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Rolls\History;

use App\Orders\Application\UseCase\PrivateQueryInteractor;
use App\Shared\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route('/api/roll/{rollId}/history', name='find_roll_history', requirements={'rollId': '^\d+$'}, methods={'GET'})
 */
#[AsController]
#[Route('/api/rolls/{rollId}/history', name: 'find_roll_history', requirements: ['rollId' => '^\d+$'], methods: ['GET'])]
final class FindRollHistoryAction extends BaseController
{
    /**
     * Class constructor.
     *
     * @param PrivateQueryInteractor $privateQueryInteractor the private query interactor
     * @param NormalizerInterface    $normalizer             the normalizer interface
     */
    public function __construct(private PrivateQueryInteractor $privateQueryInteractor, private NormalizerInterface $normalizer)
    {
    }

    /**
     * Calls the findARoll method of the privateQueryInteractor to retrieve RollData by id.
     *
     * @throws ExceptionInterface
     */
    public function __invoke(int $rollId): JsonResponse
    {
        $result = $this->privateQueryInteractor->findRollHistory($rollId);

        $result = $this->normalizer->normalize($result);

        return $this->json($result, Response::HTTP_OK, []);
    }
}
