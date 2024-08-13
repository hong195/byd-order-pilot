<?php

declare(strict_types=1);

namespace App\Orders\Infrastructure\Controller;

use App\Orders\Application\UseCase\PrivateCommandInteractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[Route('/api/rolls/{id}', name: 'update_roll', requirements: ['id' => '^\d+$'], methods: ['PUT'])]
final readonly class UpdateRoll
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Handles the update roll request.
     *
     * @param int     $id      the ID of the roll
     * @param Request $request the request object
     *
     * @return JsonResponse the JSON response
     */
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $name = $request->get('name');
        $quality = $request->get('quality');
        $rollType = $request->get('rollType');
        $length = (int) $request->get('length');
        $priority = (int) $request->get('priority');
        $qualityNotes = $request->get('qualityNotes');

        $this->privateCommandInteractor->updateRoll($id, $name, $quality, $rollType, $length, $priority, $qualityNotes);

        return new JsonResponse(['message' => 'Roll updated successfully'], Response::HTTP_CREATED);
    }
}
