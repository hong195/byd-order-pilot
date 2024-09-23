<?php

declare(strict_types=1);

namespace App\ProductionProcess\Infrastructure\Controller\Rolls;

use App\ProductionProcess\Application\UseCase\PrivateCommandInteractor;
use App\Shared\Infrastructure\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller to find a roll by ID.
 *
 * @Route("/api/rolls/{id}", requirements={"id"="\d+"}, methods={"GET"})
 */
#[AsController]
#[Route('/api/rolls/cutting-check-in/{rollId}', name: 'cutting_check_in', requirements: ['rollId' => '^\d+$'], methods: ['POST'])]
final class CuttingCheckInAction extends BaseController
{
    /**
     * Class description: this class represents a constructor for the PrivateCommandInteractor class.
     *
     * @param PrivateCommandInteractor $privateCommandInteractor the private command interactor object
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Class description: this class represents an invokable method that sends a roll to print check-in and returns a JSON response.
     *
     * @param int $rollId the ID of the roll to be sent to print check-in
     *
     * @return JsonResponse the JSON response containing a success message
     */
    public function __invoke(int $rollId): JsonResponse
    {
        $this->privateCommandInteractor->cuttingCheckIn($rollId);

        return $this->json(['message' => 'Roll was sent to cutting check-in'], Response::HTTP_OK);
    }
}
