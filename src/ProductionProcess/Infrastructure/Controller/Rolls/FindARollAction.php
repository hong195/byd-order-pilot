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
 * Controller to find a roll by ID.
 *
 * @Route("/api/rolls/{id}", requirements={"id"="\d+"}, methods={"GET"})
 */
#[AsController]
#[Route('/api/rolls/{id}', name: 'find_a_single_roll', requirements: ['id' => '^\d+$'], methods: ['GET'])]
final class FindARollAction extends BaseController
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
     * @param string $id the id of the RollData to find
     *
     * @throws ExceptionInterface
     */
    public function __invoke(string $id): JsonResponse
    {
        $result = $this->privateQueryInteractor->findARoll($id);

        $result = $this->normalizer->normalize($result);

        return $this->json($result['rollData'], Response::HTTP_OK, []);
    }
}
