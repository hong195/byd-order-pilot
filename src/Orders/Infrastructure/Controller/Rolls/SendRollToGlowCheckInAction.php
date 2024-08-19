<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller\Rolls;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
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
#[Route('/api/rolls/glow-check-in/{rollId}', name: 'send_roll_to_glow_check_in', requirements: ['rollId' => '^\d+$'], methods: ['POST'])]
final class SendRollToGlowCheckInAction extends BaseController
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
        $this->privateCommandInteractor->sendRollToGlowCheckIn($rollId);

        return $this->json(['message' => 'Roll was sent to glow check-in'], Response::HTTP_OK);
    }
}
