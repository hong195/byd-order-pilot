<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller;

use App\Orders\Application\UseCase\PrivateQueryInteractor;
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
#[Route('/api/laminations/{id}', name: 'find_a_single_laminition', requirements: ['id' => '^\d+$'], methods: ['GET'])]
final class FindALamination extends BaseController
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
     * @param int $id the id of the RollData to find
     *
     * @throws ExceptionInterface
     *
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        $result = $this->privateQueryInteractor->findALamination($id);

        $result = $this->normalizer->normalize($result);

        return $this->json($result['laminationData'], Response::HTTP_OK, []);
    }
}
