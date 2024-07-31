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
#[Route('/api/laminations/{id}', name: 'update_lamination', requirements: ['id' => '^\d+$'], methods: ['PUT'])]
final readonly class UpdateLamination
{
    /**
     * Class constructor.
     */
    public function __construct(private PrivateCommandInteractor $privateCommandInteractor)
    {
    }

    /**
     * Update a lamination record.
     *
     * @param int     $id      the id of the lamination record to update
     * @param Request $request the request object containing the updated data
     *
     * @return JsonResponse the JSON response indicating the successful update
     */
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $name = $request->get('name');
        $quality = $request->get('quality');
        $rollType = $request->get('laminationType');
        $length = (int) $request->get('length');
        $priority = (int) $request->get('priority');
        $qualityNotes = $request->get('qualityNotes');

        $this->privateCommandInteractor->updateLamination(
            $id,
            $name,
            $quality,
            $rollType,
            $length,
            $priority,
            $qualityNotes
        );

        return new JsonResponse([
            'message' => 'Lamination updated successfully',
        ], Response::HTTP_OK);
    }
}
